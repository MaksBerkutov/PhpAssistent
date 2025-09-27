<?php

namespace App\Http\Controllers;

use App\Models\Apps;
use App\Services\AppManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class AppInstallerController extends Controller
{
    protected AppManager $manager;

    public function __construct(AppManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Список установленных приложений
     */
    public function index()
    {
        $apps = Apps::all();
        return view('Apps.index', compact('apps'));
    }

    /**
     * Рендер конкретного приложения
     */
    public function open(Apps $app)
    {
        $providerClass = $app->entrypoint;

        try {
            $this->manager->integrate($providerClass);
            $html = $this->manager->renderApp($providerClass);
        } catch (\Exception $e) {
            $html = "<div class='alert alert-danger'>Ошибка: {$e->getMessage()}</div>";
        }

        return view('Apps.open', compact('app', 'html'));
    }


    /**
     * Форма для загрузки App
     */
    public function uploadForm()
    {
        return view('Apps.upload');
    }

    /**
     * Преобразует строку в безопасный slug для namespace
     */
    function makeSafeSlug(string $string): string
    {
        // Заменяем всё, что не буквы/цифры на пустоту
        $slug = preg_replace('/[^A-Za-z0-9]/', '', $string);

        // Делаем первую букву заглавной для namespace
        return ucfirst($slug);
    }
    /**
     * Установка аппса из ZIP
     */
    public function install(Request $request)
    {

        $request->validate([
            'app_zip' => 'required|file|mimes:zip|max:10240', // до 10МБ
        ]);
        $zip = $request->file('app_zip');
        $zipPath = $zip->getRealPath();

        $tempDir = storage_path('app/temp_app_' . time());
        File::makeDirectory($tempDir, 0755, true);

        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($zipPath) !== true) {
            return back()->with('error', 'Не удалось открыть ZIP-файл');
        }

        $zipArchive->extractTo($tempDir);
        $zipArchive->close();

        // Проверяем наличие manifest.json
        $manifestPath = $tempDir . '/manifest.json';
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'В ZIP отсутствует manifest.json');
        }

        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest || !isset($manifest['slug'], $manifest['name'])) {
            File::deleteDirectory($tempDir);
            return back()->with('error', 'Некорректный manifest.json');
        }

        $slug = $manifest['slug'];
        $version = $manifest['version'] ?? null;

        // Проверяем, установлен ли уже аппс
        $existingApp = Apps::where('slug', $slug)->first();
        if ($existingApp) {
            // Можно добавить проверку версии
            File::deleteDirectory(base_path('app/Apps/' . $this->makeSafeSlug($slug)));
            $existingApp->delete();
        }

        // Копируем src/ в app/Apps/{slug}
        $srcDir = $tempDir . '/src';
        $destDir = base_path('app/Apps/' .  $this->makeSafeSlug(string: $slug));
        File::makeDirectory($destDir, 0755, true);
        File::copyDirectory($srcDir, $destDir);

        // Копируем Views/ в resources/views/components/{slug} или в resources/views/apps/{slug}
        $viewsSrc = $tempDir . '/Views';
        if (File::exists($viewsSrc)) {
            $viewsDest = resource_path('views/apps/' .  $this->makeSafeSlug($slug));
            File::makeDirectory($viewsDest, 0755, true);
            File::copyDirectory($viewsSrc, $viewsDest);
        }

        // Можно копировать контроллеры отдельно или оставить внутри src/
        $controllersSrc = $tempDir . '/Controllers';
        if (File::exists($controllersSrc)) {
            $controllersDest = $destDir . '/Controllers';
            File::copyDirectory($controllersSrc, $controllersDest);
        }

        // Сохраняем запись в БД
        Apps::create([
            'name' => $manifest['name'],
            'slug' => $slug,
            'version' => $version,
            'entrypoint' => 'Apps\\' . $this->makeSafeSlug($slug) . '\\ServiceProvider',
            'description' => $manifest['description'] ?? null,
        ]);

        // Удаляем временную папку
        File::deleteDirectory($tempDir);

        return back()->with('success', "Приложение '{$manifest['name']}' успешно установлено");
    }
}

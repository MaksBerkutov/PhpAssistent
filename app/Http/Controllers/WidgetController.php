<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::all();
        return view('Widget.index', compact('widgets'));
    }

    public function create()
    {
        return view('Widget.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'widget_name' => ['required', 'string', 'max:255'],
            'accesses_key' => ['nullable', 'string', 'max:255'],
            'input_params' => ['nullable', 'string', 'max:255'],

        ]);
        $validated['is_private'] = !$validated['accesses_key'] == null;
        Widget::create($validated);
        return redirect()->route('widget')->with('success ', 'Віджет створен');
    }

    public function delete($id)
    {
        if (Auth::user()->role != "admin") return  redirect()->route("home")->with("error", "Вы не админістратор!");
        Widget::findOrFail($id)->delete();
        return redirect()->route('widget')->with('success', 'Віджет успішно видален.');
    }
    public function update(Request $request, $id)
    {
        return redirect()->route('widget')->with('success', 'Сценарій успішно оновлен.');
    }
    public function edit($id)
    {
        return view('Widget.create');
    }

    public function install(Request $request)
    {
        $request->validate([
            'widget' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('widget');

        // временная папка
        $tmpDir = storage_path('app/tmp_widget_' . time());
        File::makeDirectory($tmpDir);

        // распаковать архив
        $zip = new \ZipArchive;
        if ($zip->open($file->getRealPath()) === true) {
            $zip->extractTo($tmpDir);
            $zip->close();
        } else {
            File::deleteDirectory($tmpDir);
            return response()->json(['error' => 'Ошибка распаковки архива'], 400);
        }

        // читаем manifest.json
        $manifestPath = $tmpDir . '/manifest.json';
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($tmpDir);
            return response()->json(['error' => 'Файл manifest.json не найден'], 400);
        }

        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest || !isset($manifest['name'], $manifest['class'], $manifest['version'], $manifest['widget_name'], $manifest['accesses_key'])) {
            File::deleteDirectory($tmpDir);
            return response()->json(['error' => 'Неверный manifest.json'], 400);
        }

        $name = $manifest['name'];
        $accessesKey = $manifest['accesses_key'];
        $widgetName = $manifest['widget_name'];
        $class = $manifest['class'];
        $version = $manifest['version'];

        // проверка на версию
        $installed = DB::table('widgets')->where('name', $name)->first();
        if ($installed && version_compare($version, $installed->version, '<=')) {
            File::deleteDirectory($tmpDir);
            return response()->json(['error' => 'Установлена более новая или та же версия'], 400);
        }

        // копируем src → app/View/Components
        $targetSrc = app_path('View/Components');
        if (File::isDirectory($tmpDir . '/src')) {
            File::copyDirectory($tmpDir . '/src', $targetSrc);
        }

        // копируем views → resources/views/components
        $targetViews = resource_path('views/components');
        if (File::isDirectory($tmpDir . '/resources/views')) {
            File::copyDirectory($tmpDir . '/resources/views', $targetViews);
        }

        // копируем public → public/widgets/{name}
        $targetPublic = public_path('widgets/' . $name);
        if (File::isDirectory($tmpDir . '/public')) {
            File::ensureDirectoryExists($targetPublic);
            File::copyDirectory($tmpDir . '/public', $targetPublic);
        }

        // пробуем загрузить класс
        if (!class_exists($class)) {
            File::deleteDirectory($tmpDir);
            return response()->json(['error' => "Класс {$class} не найден после установки"], 400);
        }

        // вытаскиваем схему
        $schema = [];
        if (method_exists($class, 'getSchema')) {
            $schema = $class::getSchema();
        }

        // сохраняем в БД
        DB::table('widgets')->updateOrInsert(
            ['name' => $name],
            [
                'name' => $name,
                'widget_name' => $widgetName,
                'accesses_key' => $accessesKey,
                //'version' => $version,
                'input_params' => json_encode($schema),
                'updated_at' => now(),
                'created_at' => $installed ? $installed->created_at : now(),
                "is_private" => ($accessesKey !== null),
            ]
        );

        // очистка
        File::deleteDirectory($tmpDir);

        return redirect()->back()->with('success', "Віджет $name додан V($version)");
    }
}

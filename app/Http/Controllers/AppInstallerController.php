<?php

namespace App\Http\Controllers;

use App\Models\Apps;
use App\Services\AppManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AppInstallerController extends Controller
{
    protected AppManager $manager;

    public function __construct(AppManager $manager)
    {
        $this->manager = $manager;
    }

    public function index()
    {
        $apps = Apps::all();

        return view('Apps.index', compact('apps'));
    }

    public function open(Apps $app)
    {
        $providerClass = $app->entrypoint;

        try {
            $this->manager->integrate($providerClass);
            $html = $this->manager->renderApp($providerClass);
        } catch (\Exception $e) {
            $html = "<div class='alert alert-danger'>" . __('ui.apps.runtime_error', ['message' => $e->getMessage()]) . '</div>';
        }

        return view('Apps.open', compact('app', 'html'));
    }

    public function uploadForm()
    {
        return view('Apps.upload');
    }

    public function makeSafeSlug(string $string): string
    {
        $slug = preg_replace('/[^A-Za-z0-9]/', '', $string);

        return ucfirst($slug);
    }

    public function install(Request $request)
    {
        $request->validate([
            'app_zip' => 'required|file|mimes:zip|max:10240',
        ]);

        $zip = $request->file('app_zip');
        $zipPath = $zip->getRealPath();

        $tempDir = storage_path('app/temp_app_' . time());
        File::makeDirectory($tempDir, 0755, true);

        $zipArchive = new \ZipArchive();
        if ($zipArchive->open($zipPath) !== true) {
            return back()->with('error', __('ui.apps.errors.zip_open'));
        }

        $zipArchive->extractTo($tempDir);
        $zipArchive->close();

        $manifestPath = $tempDir . '/manifest.json';
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($tempDir);

            return back()->with('error', __('ui.apps.errors.manifest_missing'));
        }

        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest || !isset($manifest['slug'], $manifest['name'])) {
            File::deleteDirectory($tempDir);

            return back()->with('error', __('ui.apps.errors.manifest_invalid'));
        }

        $slug = $manifest['slug'];
        $version = $manifest['version'] ?? null;

        $existingApp = Apps::where('slug', $slug)->first();
        if ($existingApp) {
            File::deleteDirectory(base_path('app/Apps/' . $this->makeSafeSlug($slug)));
            $existingApp->delete();
        }

        $srcDir = $tempDir . '/src';
        $destDir = base_path('app/Apps/' . $this->makeSafeSlug($slug));
        File::makeDirectory($destDir, 0755, true);
        File::copyDirectory($srcDir, $destDir);

        $viewsSrc = $tempDir . '/Views';
        if (File::exists($viewsSrc)) {
            $viewsDest = resource_path('views/apps/' . $this->makeSafeSlug($slug));
            File::makeDirectory($viewsDest, 0755, true);
            File::copyDirectory($viewsSrc, $viewsDest);
        }

        $controllersSrc = $tempDir . '/Controllers';
        if (File::exists($controllersSrc)) {
            $controllersDest = $destDir . '/Controllers';
            File::copyDirectory($controllersSrc, $controllersDest);
        }

        Apps::create([
            'name' => $manifest['name'],
            'slug' => $slug,
            'version' => $version,
            'entrypoint' => 'Apps\\' . $this->makeSafeSlug($slug) . '\\ServiceProvider',
            'description' => $manifest['description'] ?? null,
        ]);

        File::deleteDirectory($tempDir);

        return back()->with('success', __('ui.apps.success_installed', ['name' => $manifest['name']]));
    }
}

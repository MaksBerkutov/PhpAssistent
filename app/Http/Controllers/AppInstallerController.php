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
        $apps = Apps::orderBy('name')->get();

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

    public function updateForm(Apps $app)
    {
        return view('Apps.update', compact('app'));
    }

    public function install(Request $request)
    {
        $request->validate([
            'app_zip' => 'required|file|mimes:zip|max:10240',
        ]);

        $result = $this->processArchive($request->file('app_zip'));

        if (!$result['ok']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('apps.index')->with('success', __('ui.apps.success_installed', ['name' => $result['name']]));
    }

    public function update(Request $request, Apps $app)
    {
        $request->validate([
            'app_zip' => 'required|file|mimes:zip|max:10240',
        ]);

        $result = $this->processArchive($request->file('app_zip'), $app->slug);

        if (!$result['ok']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('apps.index')->with('success', __('ui.apps.success_updated', ['name' => $result['name']]));
    }

    public function destroy(Apps $app)
    {
        $this->deleteInstalledAppFiles($app->slug);
        $name = $app->name;
        $app->delete();

        return redirect()->route('apps.index')->with('success', __('ui.apps.success_deleted', ['name' => $name]));
    }

    public function makeSafeSlug(string $string): string
    {
        $slug = preg_replace('/[^A-Za-z0-9]/', '', $string);

        return ucfirst($slug);
    }

    protected function processArchive($zipFile, ?string $expectedSlug = null): array
    {
        $zipPath = $zipFile->getRealPath();
        $tempDir = storage_path('app/temp_app_' . time() . '_' . mt_rand(1000, 9999));
        File::makeDirectory($tempDir, 0755, true);

        try {
            $zipArchive = new \ZipArchive();
            if ($zipArchive->open($zipPath) !== true) {
                return ['ok' => false, 'message' => __('ui.apps.errors.zip_open')];
            }

            $zipArchive->extractTo($tempDir);
            $zipArchive->close();

            $manifestPath = $this->findManifestPath($tempDir);
            if (!$manifestPath) {
                return ['ok' => false, 'message' => __('ui.apps.errors.manifest_missing')];
            }

            $manifestRaw = File::get($manifestPath);
            $manifestRaw = preg_replace('/^\xEF\xBB\xBF/', '', $manifestRaw);
            $manifest = json_decode($manifestRaw, true);

            if (!$manifest || !isset($manifest['slug'], $manifest['name'])) {
                $jsonError = json_last_error() !== JSON_ERROR_NONE ? json_last_error_msg() : null;

                return [
                    'ok' => false,
                    'message' => __('ui.apps.errors.manifest_invalid') . ($jsonError ? " ({$jsonError})" : ''),
                ];
            }

            $slug = (string) $manifest['slug'];
            if ($expectedSlug !== null && $slug !== $expectedSlug) {
                return [
                    'ok' => false,
                    'message' => __('ui.apps.errors.slug_mismatch', ['expected' => $expectedSlug, 'actual' => $slug]),
                ];
            }

            $appRootDir = dirname($manifestPath);
            $srcDir = $appRootDir . '/src';
            if (!File::exists($srcDir)) {
                return ['ok' => false, 'message' => __('ui.apps.errors.source_missing')];
            }

            $safeSlug = $this->makeSafeSlug($slug);
            $existingApp = Apps::where('slug', $slug)->first();
            if ($existingApp) {
                $this->deleteInstalledAppFiles($existingApp->slug);
            }

            $destDir = base_path('app/Apps/' . $safeSlug);
            File::makeDirectory($destDir, 0755, true);
            File::copyDirectory($srcDir, $destDir);

            $viewsSrc = $appRootDir . '/Views';
            if (File::exists($viewsSrc)) {
                $viewsDestUpper = resource_path('views/Apps/' . $safeSlug);
                File::makeDirectory($viewsDestUpper, 0755, true);
                File::copyDirectory($viewsSrc, $viewsDestUpper);

                $viewsDestLower = resource_path('views/apps/' . $safeSlug);
                File::makeDirectory($viewsDestLower, 0755, true);
                File::copyDirectory($viewsSrc, $viewsDestLower);
            }

            $controllersSrc = $appRootDir . '/Controllers';
            if (File::exists($controllersSrc)) {
                $controllersDest = $destDir . '/Controllers';
                File::copyDirectory($controllersSrc, $controllersDest);
            }

            $payload = [
                'name' => $manifest['name'],
                'slug' => $slug,
                'version' => $manifest['version'] ?? null,
                'entrypoint' => 'Apps\\' . $safeSlug . '\\ServiceProvider',
                'description' => $manifest['description'] ?? null,
                'schema' => $manifest['schema'] ?? null,
            ];

            if ($existingApp) {
                $existingApp->update($payload);
            } else {
                Apps::create($payload);
            }

            return ['ok' => true, 'name' => $manifest['name']];
        } finally {
            File::deleteDirectory($tempDir);
        }
    }

    protected function deleteInstalledAppFiles(string $slug): void
    {
        $safeSlug = $this->makeSafeSlug($slug);

        File::deleteDirectory(base_path('app/Apps/' . $safeSlug));
        File::deleteDirectory(resource_path('views/Apps/' . $safeSlug));
        File::deleteDirectory(resource_path('views/apps/' . $safeSlug));
    }

    protected function findManifestPath(string $baseDir): ?string
    {
        $rootManifest = $baseDir . '/manifest.json';
        if (File::exists($rootManifest)) {
            return $rootManifest;
        }

        foreach (File::allFiles($baseDir) as $file) {
            if (strtolower($file->getFilename()) === 'manifest.json') {
                return $file->getPathname();
            }
        }

        return null;
    }
}

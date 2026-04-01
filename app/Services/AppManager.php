<?php

namespace App\Services;

use App\Models\Apps;
use Illuminate\Support\Facades\File;

class AppManager
{
    protected array $apps = [];
    protected array $appMenuItems = [];
    protected array $menuItemSignatures = [];
    protected array $loadedProviders = [];

    public function __construct()
    {
        $this->loadApps();
    }

    public function loadApps(): void
    {
        foreach ($this->collectProviderClasses() as $providerClass) {
            try {
                $this->integrate($providerClass);
            } catch (\Exception $e) {
                // Skip broken app provider during preload.
            }
        }
    }

    public function getApp(string $slug)
    {
        return $this->apps[$slug] ?? null;
    }

    public function getMenuItems(): array
    {
        return $this->appMenuItems;
    }

    public function integrate(string $providerClass): ?array
    {
        if (isset($this->loadedProviders[$providerClass])) {
            return null;
        }

        $this->ensureProviderClassLoaded($providerClass);

        if (!class_exists($providerClass)) {
            throw new \Exception("Class {$providerClass} not found");
        }

        /** @var \Apps\BaseAppServiceProvider $provider */
        $provider = new $providerClass();

        if (method_exists($provider, 'registerRoutes')) {
            $provider->registerRoutes();
        }

        if (method_exists($provider, 'registerWidgets')) {
            $provider->registerWidgets();
        }

        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }

        $this->collectProviderMenuItems($provider);
        $this->loadedProviders[$providerClass] = true;

        if (method_exists($provider, 'getSchema')) {
            return $provider->getSchema();
        }

        return null;
    }

    public function renderApp(string $providerClass, array $data = [])
    {
        $this->ensureProviderClassLoaded($providerClass);

        if (!class_exists($providerClass)) {
            throw new \Exception("Class {$providerClass} not found");
        }

        $provider = new $providerClass();

        if (!method_exists($provider, 'render')) {
            throw new \Exception("App {$providerClass} does not implement render method");
        }

        return $provider->render($data);
    }

    protected function ensureProviderClassLoaded(string $providerClass): void
    {
        if (class_exists($providerClass)) {
            return;
        }

        $prefix = 'Apps\\';
        if (!str_starts_with($providerClass, $prefix)) {
            return;
        }

        $baseProviderPath = base_path('app/Apps/BaseAppServiceProvider.php');
        if (!class_exists('Apps\\BaseAppServiceProvider') && is_file($baseProviderPath)) {
            require_once $baseProviderPath;
        }

        $relative = substr($providerClass, strlen($prefix));
        $filePath = base_path('app/Apps/' . str_replace('\\', '/', $relative) . '.php');

        if (is_file($filePath)) {
            require_once $filePath;
        }
    }

    protected function collectProviderMenuItems(\Apps\BaseAppServiceProvider $provider): void
    {
        foreach ($provider->menuItems() as $item) {
            if (!is_array($item)) {
                continue;
            }

            $hasUrl = !empty($item['url']);
            $hasRoute = !empty($item['route']);
            if ((!$hasUrl && !$hasRoute) || empty($item['label'])) {
                continue;
            }

            $normalized = [
                'label' => (string) $item['label'],
                'url' => $hasUrl ? (string) $item['url'] : null,
                'route' => $hasRoute ? (string) $item['route'] : null,
                'route_params' => (array) ($item['route_params'] ?? []),
                'image' => (string) ($item['image'] ?? 'flash-outline'),
                'guard' => (string) ($item['guard'] ?? ''),
            ];

            $signature = implode('|', [
                $normalized['label'],
                (string) ($normalized['url'] ?? ''),
                (string) ($normalized['route'] ?? ''),
                json_encode($normalized['route_params']),
                $normalized['image'],
                $normalized['guard'],
            ]);
            if (!isset($this->menuItemSignatures[$signature])) {
                $this->menuItemSignatures[$signature] = true;
                $this->appMenuItems[] = $normalized;
            }
        }
    }

    protected function collectProviderClasses(): array
    {
        $providers = [];

        try {
            foreach (Apps::all() as $appRecord) {
                if (!empty($appRecord->entrypoint)) {
                    $providers[] = (string) $appRecord->entrypoint;
                }
            }
        } catch (\Exception $e) {
            // Continue with filesystem discovery.
        }

        $appsDir = base_path('app/Apps');
        if (is_dir($appsDir)) {
            foreach (File::directories($appsDir) as $dir) {
                $slug = basename($dir);
                $providerFile = $dir . DIRECTORY_SEPARATOR . 'ServiceProvider.php';
                if (!is_file($providerFile)) {
                    continue;
                }

                $providers[] = 'Apps\\' . $slug . '\\ServiceProvider';
            }
        }

        return array_values(array_unique($providers));
    }
}

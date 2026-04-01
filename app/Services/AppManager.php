<?php

namespace App\Services;

use App\Models\Apps;
use Illuminate\Support\Facades\Route;
use DB;

class AppManager
{
    protected array $apps = [];

    public function __construct()
    {
        $this->loadApps();
    }

    /**
     * Загружаем все установленные приложения из БД
     */
    public function loadApps(): void
    {
        try {
            $installedApps = Apps::all();

            foreach ($installedApps as $appRecord) {
                $providerClass = $appRecord->entrypoint;
                try {
                    $this->integrate($providerClass);
                } catch (\Exception $e) {
                }
            }
        } catch (\Exception $e) {
            //TODO Log
        }

    }

    /**
     * Получить экземпляр аппса по slug
     */
    public function getApp(string $slug)
    {
        return $this->apps[$slug] ?? null;
    }


    /**
     * Интегрировать аппс в систему
     *
     * @param string $providerClass - полный класс ServiceProvider аппса
     * @return array|null - схема настроек аппса
     */
    public function integrate(string $providerClass): ?array
    {
        if (!class_exists($providerClass)) {
            throw new \Exception("Класс {$providerClass} не найден");
        }

        /** @var \Apps\BaseAppServiceProvider $provider */
        $provider = new $providerClass();

        // Регистрируем маршруты
        if (method_exists($provider, 'registerRoutes')) {
            $provider->registerRoutes();
        }

        // Регистрируем виджеты в БД (только для отображения)
        if (method_exists($provider, 'registerWidgets')) {
            $provider->registerWidgets();
        }

        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
        // Возвращаем схему настроек (для админки)
        if (method_exists($provider, 'getSchema')) {
            return $provider->getSchema();
        }


        return null;
    }

    /**
     * Рендер интерфейса аппса для админки
     *
     * @param string $providerClass
     * @param array $data - данные для передачи в аппс
     */
    public function renderApp(string $providerClass, array $data = [])
    {
        if (!class_exists($providerClass)) {
            throw new \Exception("Класс {$providerClass} не найден");
        }

        $provider = new $providerClass();

        if (!method_exists($provider, 'render')) {
            throw new \Exception("App {$providerClass} не имеет метода render");
        }

        return $provider->render($data);
    }
}

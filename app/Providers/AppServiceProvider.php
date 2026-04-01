<?php

namespace App\Providers;

use App\Services\AES;
use App\Services\AppManager;
use App\Services\DevicesReqest;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AES::class, function ($app) {

            return new AES();
        });
        $this->app->singleton(DevicesReqest::class, function ($app) {

            return new DevicesReqest();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appManager = new AppManager();

        // Сохраняем в контейнер, чтобы потом использовать
        app()->instance(AppManager::class, $appManager);
    }
}

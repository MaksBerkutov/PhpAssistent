<?php

namespace Apps\TelegramBot;

use Apps\BaseAppServiceProvider;
use App\Events\ScenarioTriggered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


class ServiceProvider extends BaseAppServiceProvider
{
    protected array $config = [];

    public function __construct()
    {
        // загружаем настройки из БД
        $this->config = DB::table('app_settings')
            ->where('app', 'telegram-bot')
            ->pluck('value', 'key')
            ->toArray();
    }

    public static function getSchema(): array
    {
        return [
            ['name' => 'api_token', 'type' => 'string', 'label' => 'API Token'],
            ['name' => 'chat_id', 'type' => 'string', 'label' => 'Chat ID'],
            ['name' => 'whitelist', 'type' => 'array', 'label' => 'Белый список сценариев']
        ];
    }
    public function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('apps/telegram')
            ->group(function () {
                Route::get('/settings', [\Apps\TelegramBot\Controllers\SettingsController::class, 'index'])
                    ->name('apps.telegram.settings');
                Route::post('/settings/save', [\Apps\TelegramBot\Controllers\SettingsController::class, 'save'])
                    ->name('apps.telegram.save');
            });
    }

    public function boot(): void
    { // Регистрируем hint для view
        $viewsPath = __DIR__ . '/Views';
        View::addNamespace('telegrambot', $viewsPath);

        // Подписка на событие
        Event::listen(ScenarioTriggered::class, function ($event) {
            $this->handleScenario($event->scenarioKey, $event->payload);
        });
    }

    protected function handleScenario(string $scenarioKey, array $payload)
    {
        $whitelist = $this->config['whitelist'] ?? [];

        if (!in_array($scenarioKey, $whitelist)) return; // не разрешён сценарий

        $token = $this->config['api_token'] ?? null;
        $chatId = $this->config['chat_id'] ?? null;

        if (!$token || !$chatId) return;

        $message = $payload['message'] ?? "Сценарий сработал: {$scenarioKey}";

        file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chatId}&text=" . urlencode($message));
    }

    public function render(array $data = [])
    {
        $this->registerRoutes();
        return View::make('telegrambot::settings', ['settings' => $this->config])->render();
    }
}

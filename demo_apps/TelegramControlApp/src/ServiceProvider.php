<?php

namespace Apps\Telegramcontrol;

use Apps\BaseAppServiceProvider;
use Apps\Telegramcontrol\Controllers\BotController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends BaseAppServiceProvider
{
    public static function getSchema(): array
    {
        return [
            'title' => 'Telegram Control settings',
            'fields' => [
                ['name' => 'bot_token', 'type' => 'text', 'label' => 'Bot token'],
                ['name' => 'chat_id', 'type' => 'text', 'label' => 'Default chat ID'],
                ['name' => 'webhook_secret', 'type' => 'text', 'label' => 'Webhook secret'],
                ['name' => 'delivery_mode', 'type' => 'select', 'label' => 'Delivery mode (webhook|polling)'],
            ],
        ];
    }

    public function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])->prefix('/apps/telegramcontrol')->group(function () {
            Route::get('/', [BotController::class, 'dashboard'])->name('apps.telegramcontrol.dashboard');
            Route::post('/settings', [BotController::class, 'saveSettings'])->name('apps.telegramcontrol.settings');

            Route::post('/mode', [BotController::class, 'setMode'])->name('apps.telegramcontrol.mode');
            Route::post('/poll/run-once', [BotController::class, 'runPollingOnce'])->name('apps.telegramcontrol.poll.run_once');

            Route::post('/webhook/set', [BotController::class, 'setWebhook'])->name('apps.telegramcontrol.webhook.set');
            Route::post('/webhook/delete', [BotController::class, 'deleteWebhook'])->name('apps.telegramcontrol.webhook.delete');
            Route::post('/send-test', [BotController::class, 'sendTest'])->name('apps.telegramcontrol.send_test');
        });

        Route::middleware('web')
            ->withoutMiddleware([VerifyCsrfToken::class])
            ->post('/apps/telegramcontrol/webhook/{secret}', [BotController::class, 'webhook'])
            ->name('apps.telegramcontrol.webhook');
    }

    public function menuItems(): array
    {
        return [
            [
                'label' => 'Telegram Bot',
                'route' => 'apps.telegramcontrol.dashboard',
                'image' => 'paper-plane-outline',
                'guard' => '',
            ],
        ];
    }

    public function render(array $data = [])
    {
        return view('Apps.Telegramcontrol.inline', [
            'configured' => app(BotController::class)->isConfigured(),
            'webhookUrl' => app(BotController::class)->webhookUrl(),
        ])->render();
    }
}

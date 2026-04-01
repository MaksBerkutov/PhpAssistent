<?php

namespace Apps\Statusbeacon;

use Apps\BaseAppServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends BaseAppServiceProvider
{
    public static function getSchema(): array
    {
        return [
            'title' => 'Status Beacon settings',
            'fields' => [
                [
                    'name' => 'show_uptime',
                    'type' => 'boolean',
                    'default' => true,
                    'label' => 'Show session uptime',
                ],
                [
                    'name' => 'refresh_seconds',
                    'type' => 'number',
                    'default' => 30,
                    'label' => 'Refresh interval (seconds)',
                ],
            ],
        ];
    }

    public function registerRoutes(): void
    {
        Route::middleware(['web', 'auth'])->get('/apps/statusbeacon/health', function () {
            return response()->view('Apps.Statusbeacon.health', [
                'serverTime' => now()->toDateTimeString(),
                'user' => Auth::user()?->name,
            ]);
        })->name('apps.statusbeacon.health');

        Route::middleware(['web', 'auth'])->get('/apps/statusbeacon/ping', function () {
            return response()->json([
                'ok' => true,
                'server_time' => now()->toDateTimeString(),
                'user' => Auth::user()?->name,
            ]);
        })->name('apps.statusbeacon.ping');
    }

    public function menuItems(): array
    {
        return [
            [
                'label' => 'Status Beacon',
                'route' => 'apps.statusbeacon.health',
                'image' => 'pulse-outline',
                'guard' => '',
            ],
        ];
    }

    public function render(array $data = [])
    {
        $tips = [
            'РџСЂРѕРІРµСЂСЊС‚Рµ СЃС†РµРЅР°СЂРёРё СЃ С‚Р°Р№РјРµСЂР°РјРё РїРµСЂРµРґ СЃРЅРѕРј.',
            'Р’С‹РЅРµСЃРёС‚Рµ РєСЂРёС‚РёС‡РЅС‹Рµ СѓСЃС‚СЂРѕР№СЃС‚РІР° РІ РѕС‚РґРµР»СЊРЅС‹Р№ РІРёРґР¶РµС‚.',
            'РџСЂРѕРІРµСЂСЊС‚Рµ РїРѕРґС‚РІРµСЂР¶РґРµРЅРёРµ email РґР»СЏ СѓРІРµРґРѕРјР»РµРЅРёР№.',
            'Р”Р°РІР°Р№С‚Рµ РїРѕРЅСЏС‚РЅС‹Рµ РёРјРµРЅР° СѓСЃС‚СЂРѕР№СЃС‚РІР°Рј Рё СЃС†РµРЅР°Рј.',
        ];

        return view('Apps.Statusbeacon.dashboard', [
            'userName' => Auth::user()?->name ?? 'User',
            'serverTime' => now()->format('Y-m-d H:i:s'),
            'tip' => $tips[array_rand($tips)],
        ])->render();
    }
}

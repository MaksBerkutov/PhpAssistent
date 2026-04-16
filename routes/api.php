<?php

use App\Http\Controllers\PublicApi\DashboardController as PublicApiDashboardController;
use App\Http\Controllers\PublicApi\DeviceController as PublicApiDeviceController;
use App\Http\Controllers\PublicApi\ProfileController as PublicApiProfileController;
use App\Http\Controllers\PublicApi\ScenarioController as PublicApiScenarioController;
use App\Http\Controllers\PublicApi\VoiceCommandController as PublicApiVoiceCommandController;
use App\Http\Controllers\PublicApi\WidgetController as PublicApiWidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [\App\Http\Controllers\ApiAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\ApiAuthController::class, 'logout']);
    Route::get('/commands', [\App\Http\Controllers\VoiceController::class, 'getAllVoicesCommands']);
    Route::post('/command/action', [\App\Http\Controllers\VoiceController::class, 'Go_Command']);
});

Route::prefix('public')
    ->middleware(['auth:sanctum', 'public-api.access'])
    ->group(function () {
        Route::get('/me', [PublicApiProfileController::class, 'show'])
            ->middleware('public-api.ability:public-api.profile.read');

        Route::get('/devices', [PublicApiDeviceController::class, 'index'])
            ->middleware('public-api.ability:public-api.devices.read');
        Route::post('/devices', [PublicApiDeviceController::class, 'store'])
            ->middleware('public-api.ability:public-api.devices.write');
        Route::get('/devices/{device}', [PublicApiDeviceController::class, 'show'])
            ->middleware('public-api.ability:public-api.devices.read');
        Route::put('/devices/{device}', [PublicApiDeviceController::class, 'update'])
            ->middleware('public-api.ability:public-api.devices.write');
        Route::delete('/devices/{device}', [PublicApiDeviceController::class, 'destroy'])
            ->middleware('public-api.ability:public-api.devices.write');
        Route::post('/devices/{device}/commands', [PublicApiDeviceController::class, 'sendCommand'])
            ->middleware('public-api.ability:public-api.devices.command');
        Route::get('/devices/{device}/configuration', [PublicApiDeviceController::class, 'configuration'])
            ->middleware('public-api.ability:public-api.devices.configuration.read');
        Route::put('/devices/{device}/configuration', [PublicApiDeviceController::class, 'updateConfiguration'])
            ->middleware('public-api.ability:public-api.devices.configuration.write');
        Route::post('/devices/{device}/firmware', [PublicApiDeviceController::class, 'firmware'])
            ->middleware('public-api.ability:public-api.devices.firmware');

        Route::get('/voice-commands', [PublicApiVoiceCommandController::class, 'index'])
            ->middleware('public-api.ability:public-api.voice.read');
        Route::post('/voice-commands', [PublicApiVoiceCommandController::class, 'store'])
            ->middleware('public-api.ability:public-api.voice.write');
        Route::post('/voice-commands/execute', [PublicApiVoiceCommandController::class, 'execute'])
            ->middleware('public-api.ability:public-api.voice.execute');
        Route::get('/voice-commands/{voiceCommand}', [PublicApiVoiceCommandController::class, 'show'])
            ->middleware('public-api.ability:public-api.voice.read');
        Route::put('/voice-commands/{voiceCommand}', [PublicApiVoiceCommandController::class, 'update'])
            ->middleware('public-api.ability:public-api.voice.write');
        Route::delete('/voice-commands/{voiceCommand}', [PublicApiVoiceCommandController::class, 'destroy'])
            ->middleware('public-api.ability:public-api.voice.write');

        Route::get('/scenarios', [PublicApiScenarioController::class, 'index'])
            ->middleware('public-api.ability:public-api.scenarios.read');
        Route::post('/scenarios', [PublicApiScenarioController::class, 'store'])
            ->middleware('public-api.ability:public-api.scenarios.write');
        Route::get('/scenarios/{scenario}', [PublicApiScenarioController::class, 'show'])
            ->middleware('public-api.ability:public-api.scenarios.read');
        Route::put('/scenarios/{scenario}', [PublicApiScenarioController::class, 'update'])
            ->middleware('public-api.ability:public-api.scenarios.write');
        Route::delete('/scenarios/{scenario}', [PublicApiScenarioController::class, 'destroy'])
            ->middleware('public-api.ability:public-api.scenarios.write');

        Route::get('/dashboard', [PublicApiDashboardController::class, 'index'])
            ->middleware('public-api.ability:public-api.dashboard.read');
        Route::post('/dashboard', [PublicApiDashboardController::class, 'store'])
            ->middleware('public-api.ability:public-api.dashboard.write');
        Route::get('/dashboard/{dashboard}', [PublicApiDashboardController::class, 'show'])
            ->middleware('public-api.ability:public-api.dashboard.read');
        Route::put('/dashboard/{dashboard}', [PublicApiDashboardController::class, 'update'])
            ->middleware('public-api.ability:public-api.dashboard.write');
        Route::delete('/dashboard/{dashboard}', [PublicApiDashboardController::class, 'destroy'])
            ->middleware('public-api.ability:public-api.dashboard.write');

        Route::get('/widgets', [PublicApiWidgetController::class, 'index'])
            ->middleware('public-api.ability:public-api.widgets.read');
    });

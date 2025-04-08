<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('IOT')->group(function () {
    Route::post('/iot/receive', [App\Http\Controllers\IOTController::class,'receive']);
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\UserController::class,'index'])->name('login');
    Route::post('/login', [App\Http\Controllers\UserController::class,'login'])->name('authentication');
    Route::post('/register', [App\Http\Controllers\UserController::class,'create'])->name('register');
});
Route::middleware('auth')->group(function () {
    //Home

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');
    //Devices
    Route::get('/devices', [App\Http\Controllers\DeviceController::class, 'index'])->name('devices');
    Route::post('/devices', [App\Http\Controllers\DeviceController::class, 'send_command'])->name('devices.send');
    Route::get('/devices/create', [App\Http\Controllers\DeviceController::class, 'create'])->name('devices.create');
    Route::post('/devices/create', [App\Http\Controllers\DeviceController::class, 'store'])->name('devices.store');
    Route::get('/devices/configure', [App\Http\Controllers\DeviceController::class, 'getConfigure'])->name('devices.configure');
    Route::post('/devices/configure', [App\Http\Controllers\DeviceController::class, 'setConfigure'])->name('devices.configure');
    Route::post('/devices/firmware', [App\Http\Controllers\DeviceController::class, 'upload_firmware'])->name('devices.firmware');
    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/widget', [App\Http\Controllers\DashboardController::class, 'create'])->name('dashboard.widget');
    Route::get('/dashboard/widget/{access}', [App\Http\Controllers\DashboardController::class, 'create'])->name('dashboard.widget.private');
    Route::get('/dashboard/widget/{id}/add', [App\Http\Controllers\DashboardController::class, 'add'])->name('dashboard.widget.add');
    Route::post('/dashboard/widget', [App\Http\Controllers\DashboardController::class, 'store'])->name('dashboard.store');
    //Widget
    Route::get('/widget', [App\Http\Controllers\WidgetController::class, 'index'])->middleware('checkRole:admin')->name('widget');
    Route::get('/widget/create', [App\Http\Controllers\WidgetController::class, 'create'])->middleware('checkRole:admin')->name('widget.create');
    Route::post('/widget/create', [App\Http\Controllers\WidgetController::class, 'store'])->middleware('checkRole:admin')->name('widget.store');
    Route::get('/widget/{id}/edit', [App\Http\Controllers\WidgetController::class, 'edit'])->middleware('checkRole:admin')->name('widget.edit');
    Route::delete('/widget/{id}', [App\Http\Controllers\WidgetController::class, 'delete'])->middleware('checkRole:admin')->name('widget.delete');
    Route::put('/widget/{id}', [App\Http\Controllers\WidgetController::class, 'update'])->middleware('checkRole:admin')->name('scenario.update');

    //Scenario
    Route::get('/scenario', [App\Http\Controllers\ScenarioController::class, 'index'])->name('scenario');
    Route::get('/scenario/create', [App\Http\Controllers\ScenarioController::class, 'create'])->name('scenario.create');
    Route::post('/scenario/create', [App\Http\Controllers\ScenarioController::class, 'store'])->name('scenario.store');;
    Route::get('/scenario/{id}/edit', [App\Http\Controllers\ScenarioController::class, 'edit'])->name('scenario.edit');
    Route::delete('/scenario/{id}', [App\Http\Controllers\ScenarioController::class, 'delete'])->name('scenario.delete');
    Route::put('/scenario/{id}', [App\Http\Controllers\ScenarioController::class, 'update'])->name('scenario.update');
    //voice
    Route::get('/voice', [App\Http\Controllers\VoiceController::class, 'index'])->name('voice');
    Route::get('/voice/create', [App\Http\Controllers\VoiceController::class, 'create'])->name('voice.create');
    Route::post('/voice/create', [App\Http\Controllers\VoiceController::class, 'store'])->name('voice.store');
    Route::post('/voice/command', [App\Http\Controllers\VoiceController::class, 'command'])->name('voice.command');
    //home


    Route::get('/logout', [App\Http\Controllers\UserController::class,'logout'])->name('logout');

});

Route::get('/email', function (){return view('user.verify-email');})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\UserController::class,'verify'])->middleware(['auth','signed'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\UserController::class,'send_verify'])->middleware(['auth','throttle:3,1'])->name('verification.send');
//verified auth

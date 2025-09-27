<?php

namespace Apps\TelegramBot\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController
{
    public function index()
    {
        $settings = DB::table('app_settings')
            ->where('app', 'telegram-bot')
            ->pluck('value', 'key')
            ->toArray();

        return view('telegrambot::settings', compact('settings'));
    }

    public function save(Request $request)
    {
        $data = $request->only(['api_token', 'chat_id', 'whitelist']);
        foreach ($data as $key => $value) {
            DB::table('app_settings')->updateOrInsert(
                ['app' => 'telegram-bot', 'key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        return redirect()->back()->with('success', 'Настройки сохранены');
    }
}

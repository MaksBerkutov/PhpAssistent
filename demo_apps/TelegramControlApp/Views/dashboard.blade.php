@extends('layouts.menu')
@section('title', 'Telegram Bot')

@section('styles')
    <style>
        .tg-grid { display: grid; gap: 12px; max-width: 980px; }
        .tg-card { border: 1px solid var(--line); border-radius: 16px; background: var(--surface-strong); box-shadow: var(--shadow-card); padding: 16px; }
        .tg-kv { display: grid; grid-template-columns: 180px 1fr; gap: 8px; margin: 8px 0; }
        .tg-label { color: var(--ink-soft); font-size: .84rem; }
        .tg-value { color: var(--ink); font-weight: 600; word-break: break-all; }
        .tg-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .tg-note { margin-top: 10px; color: var(--ink-soft); font-size: .85rem; }
        .tg-status { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 10px; font-size: .75rem; font-weight: 700; border: 1px solid var(--line); }
        .tg-status.ok { background: rgba(47,157,129,.16); color: #2f9d81; border-color: rgba(47,157,129,.35); }
        .tg-status.bad { background: rgba(216,100,44,.16); color: #d8642c; border-color: rgba(216,100,44,.35); }
    </style>
@endsection

@section('content')
    <div class="tg-grid">
        <section class="tg-card">
            <h3 class="mb-2">Telegram Bot Control</h3>
            <span class="tg-status {{ $configured ? 'ok' : 'bad' }}">{{ $configured ? 'configured' : 'not configured' }}</span>

            <div class="tg-kv mt-3"><span class="tg-label">Delivery mode</span><span class="tg-value">{{ $deliveryMode }}</span></div>
            <div class="tg-kv"><span class="tg-label">Bot token</span><span class="tg-value">{{ $tokenMasked ?: '-' }}</span></div>
            <div class="tg-kv"><span class="tg-label">Default chat ID</span><span class="tg-value">{{ $chatId ?: '-' }}</span></div>
            <div class="tg-kv"><span class="tg-label">Webhook secret</span><span class="tg-value">{{ $webhookSecret ?: '-' }}</span></div>
            <div class="tg-kv"><span class="tg-label">Webhook URL</span><span class="tg-value">{{ $webhookUrl ?: '-' }}</span></div>
            <div class="tg-kv"><span class="tg-label">Polling offset</span><span class="tg-value">{{ $lastUpdateOffset }}</span></div>

            <div class="tg-actions">
                <form method="POST" action="{{ route('apps.telegramcontrol.mode') }}">
                    @csrf
                    <input type="hidden" name="mode" value="webhook">
                    <button class="btn btn-outline-primary" type="submit">Use Webhook</button>
                </form>
                <form method="POST" action="{{ route('apps.telegramcontrol.mode') }}">
                    @csrf
                    <input type="hidden" name="mode" value="polling">
                    <button class="btn btn-outline-primary" type="submit">Use Polling</button>
                </form>
                <form method="POST" action="{{ route('apps.telegramcontrol.poll.run_once') }}">
                    @csrf
                    <button class="btn btn-success" type="submit">Run polling once</button>
                </form>
            </div>
        </section>

        <section class="tg-card">
            <h5 class="mb-3">Settings</h5>
            <form method="POST" action="{{ route('apps.telegramcontrol.settings') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="bot_token">Bot token</label>
                    <input id="bot_token" class="form-control" name="bot_token" placeholder="123456:ABC..." autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="chat_id">Default chat ID</label>
                    <input id="chat_id" class="form-control" name="chat_id" placeholder="e.g. 123456789" autocomplete="off">
                </div>
                <button class="btn btn-primary" type="submit">Save settings</button>
            </form>

            <div class="tg-actions">
                <form method="POST" action="{{ route('apps.telegramcontrol.webhook.set') }}">@csrf <button class="btn btn-outline-primary" type="submit">Set webhook</button></form>
                <form method="POST" action="{{ route('apps.telegramcontrol.webhook.delete') }}">@csrf <button class="btn btn-outline-danger" type="submit">Delete webhook</button></form>
                <form method="POST" action="{{ route('apps.telegramcontrol.send_test') }}">@csrf <button class="btn btn-success" type="submit">Send test message</button></form>
            </div>

            <p class="tg-note">Bot commands: /help, /status, /devices, /scenarios, /ping</p>
        </section>
    </div>
@endsection

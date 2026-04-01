<?php

namespace Apps\Telegramcontrol\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BotController extends Controller
{
    private const APP = 'telegramcontrol';

    public function dashboard()
    {
        return view('Apps.Telegramcontrol.dashboard', [
            'tokenMasked' => $this->maskToken((string) $this->getSetting('bot_token', '')),
            'chatId' => (string) $this->getSetting('chat_id', ''),
            'webhookSecret' => (string) $this->getSetting('webhook_secret', ''),
            'webhookUrl' => $this->webhookUrl(),
            'configured' => $this->isConfigured(),
            'deliveryMode' => $this->deliveryMode(),
            'lastUpdateOffset' => (string) $this->getSetting('update_offset', '0'),
        ]);
    }

    public function saveSettings(Request $request)
    {
        $data = $request->validate([
            'bot_token' => ['nullable', 'string', 'max:255'],
            'chat_id' => ['nullable', 'string', 'max:255'],
        ]);

        $token = trim((string) ($data['bot_token'] ?? ''));
        $chatId = trim((string) ($data['chat_id'] ?? ''));

        if ($token !== '') {
            $this->setSetting('bot_token', $token);
        }

        if ($chatId !== '') {
            $this->setSetting('chat_id', $chatId);
        }

        if (!$this->getSetting('webhook_secret')) {
            $this->setSetting('webhook_secret', Str::random(32));
        }

        if (!$this->getSetting('delivery_mode')) {
            $this->setSetting('delivery_mode', 'webhook');
        }

        return back()->with('success', 'Telegram settings saved.');
    }

    public function setMode(Request $request)
    {
        $mode = (string) $request->validate([
            'mode' => ['required', 'in:webhook,polling'],
        ])['mode'];

        $this->setSetting('delivery_mode', $mode);

        if ($mode === 'polling') {
            $this->tryDeleteWebhookSilently();
        }

        return back()->with('success', "Delivery mode switched to {$mode}.");
    }

    public function setWebhook()
    {
        $token = (string) $this->getSetting('bot_token', '');
        $secret = (string) $this->getSetting('webhook_secret', '');

        if ($token === '') {
            return back()->with('error', 'Bot token is empty.');
        }

        if ($secret === '') {
            $secret = Str::random(32);
            $this->setSetting('webhook_secret', $secret);
        }

        $url = $this->webhookUrl();
        if (!$url) {
            return back()->with('error', 'Webhook URL is not available.');
        }

        $response = $this->callTelegram('setWebhook', [
            'url' => $url,
            'allowed_updates' => ['message'],
        ]);

        if (!$response['ok']) {
            return back()->with('error', 'Telegram setWebhook failed: ' . $response['raw']);
        }

        $this->setSetting('delivery_mode', 'webhook');

        return back()->with('success', 'Webhook has been set.');
    }

    public function deleteWebhook()
    {
        $response = $this->callTelegram('deleteWebhook', ['drop_pending_updates' => false]);

        if (!$response['ok']) {
            return back()->with('error', 'Telegram deleteWebhook failed: ' . $response['raw']);
        }

        return back()->with('success', 'Webhook has been removed.');
    }

    public function runPollingOnce()
    {
        if ($this->deliveryMode() !== 'polling') {
            return back()->with('error', 'Switch to polling mode first.');
        }

        $token = (string) $this->getSetting('bot_token', '');
        if ($token === '') {
            return back()->with('error', 'Bot token is empty.');
        }

        $offset = (int) $this->getSetting('update_offset', '0');
        $response = $this->callTelegram('getUpdates', [
            'offset' => $offset,
            'timeout' => 0,
            'allowed_updates' => ['message'],
        ]);

        if (!$response['ok']) {
            return back()->with('error', 'Polling failed: ' . $response['raw']);
        }

        $updates = $response['result'];
        $processed = 0;
        $maxUpdateId = $offset;

        foreach ($updates as $update) {
            $processed++;
            $maxUpdateId = max($maxUpdateId, (int) ($update['update_id'] ?? 0) + 1);
            $this->processIncomingUpdate($update);
        }

        if ($maxUpdateId > $offset) {
            $this->setSetting('update_offset', (string) $maxUpdateId);
        }

        return back()->with('success', "Polling done. Processed {$processed} update(s).");
    }

    public function sendTest()
    {
        $token = (string) $this->getSetting('bot_token', '');
        $chatId = (string) $this->getSetting('chat_id', '');

        if ($token === '' || $chatId === '') {
            return back()->with('error', 'Bot token or chat ID is empty.');
        }

        $sent = $this->sendMessage($token, $chatId, "Test from PhpAssistant at " . now()->toDateTimeString());

        if (!$sent) {
            return back()->with('error', 'Unable to send test message.');
        }

        return back()->with('success', 'Test message sent.');
    }

    public function webhook(Request $request, string $secret)
    {
        $storedSecret = (string) $this->getSetting('webhook_secret', '');
        $token = (string) $this->getSetting('bot_token', '');

        if ($storedSecret === '' || !hash_equals($storedSecret, $secret) || $token === '') {
            return response()->json(['ok' => false], 403);
        }

        $this->processIncomingUpdate($request->all());

        return response()->json(['ok' => true]);
    }

    public function isConfigured(): bool
    {
        return (string) $this->getSetting('bot_token', '') !== '';
    }

    public function webhookUrl(): ?string
    {
        $secret = (string) $this->getSetting('webhook_secret', '');
        if ($secret === '') {
            return null;
        }

        try {
            return route('apps.telegramcontrol.webhook', ['secret' => $secret]);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function deliveryMode(): string
    {
        $mode = (string) $this->getSetting('delivery_mode', 'webhook');

        return in_array($mode, ['webhook', 'polling'], true) ? $mode : 'webhook';
    }

    protected function processIncomingUpdate(array $update): void
    {
        $token = (string) $this->getSetting('bot_token', '');
        $text = trim((string) data_get($update, 'message.text', ''));
        $chatId = (string) data_get($update, 'message.chat.id', '');

        if ($chatId !== '' && !$this->getSetting('chat_id')) {
            $this->setSetting('chat_id', $chatId);
        }

        if ($text === '' || $chatId === '' || $token === '') {
            return;
        }

        $answer = $this->handleCommand($text);
        $this->sendMessage($token, $chatId, $answer);
    }

    protected function handleCommand(string $text): string
    {
        $command = mb_strtolower(trim($text));

        if ($command === '/start' || $command === '/help') {
            return implode("\n", [
                "PhpAssistant Telegram Bot",
                "Mode: " . $this->deliveryMode(),
                "Commands:",
                "/status - workspace status",
                "/devices - list first devices",
                "/scenarios - latest scenarios",
                "/ping - bot health check",
            ]);
        }

        if ($command === '/ping') {
            return 'pong ' . now()->format('H:i:s');
        }

        if ($command === '/status') {
            return $this->buildStatusMessage();
        }

        if ($command === '/devices') {
            return $this->buildDevicesMessage();
        }

        if ($command === '/scenarios') {
            return $this->buildScenariosMessage();
        }

        return "Unknown command. Send /help.";
    }

    protected function buildStatusMessage(): string
    {
        $devices = Device::count();
        $scenarios = Scenario::count();

        return implode("\n", [
            "Workspace status",
            "Devices: {$devices}",
            "Scenarios: {$scenarios}",
            "Mode: " . $this->deliveryMode(),
            "Server: " . now()->toDateTimeString(),
        ]);
    }

    protected function buildDevicesMessage(): string
    {
        $list = Device::query()->latest('id')->limit(10)->get(['id', 'name', 'name_board']);
        if ($list->isEmpty()) {
            return 'No devices found.';
        }

        $lines = ['Devices:'];
        foreach ($list as $device) {
            $title = $device->name ?: ('Device #' . $device->id);
            $board = $device->name_board ? " ({$device->name_board})" : '';
            $lines[] = "- {$title}{$board}";
        }

        return implode("\n", $lines);
    }

    protected function buildScenariosMessage(): string
    {
        $list = Scenario::query()->latest('id')->limit(10)->get(['id', 'devices_id', 'key', 'value']);
        if ($list->isEmpty()) {
            return 'No scenarios found.';
        }

        $lines = ['Latest scenarios:'];
        foreach ($list as $scenario) {
            $lines[] = "- #{$scenario->id} device {$scenario->devices_id}: {$scenario->key}={$scenario->value}";
        }

        return implode("\n", $lines);
    }

    protected function sendMessage(string $token, string $chatId, string $text): bool
    {
        $response = Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        return $response->ok() && (bool) ($response->json('ok') ?? false);
    }

    protected function callTelegram(string $method, array $payload = []): array
    {
        $token = (string) $this->getSetting('bot_token', '');
        if ($token === '') {
            return ['ok' => false, 'raw' => 'Bot token is empty.', 'result' => []];
        }

        $response = Http::timeout(12)->post("https://api.telegram.org/bot{$token}/{$method}", $payload);
        $json = $response->json();

        return [
            'ok' => $response->ok() && (bool) ($json['ok'] ?? false),
            'raw' => (string) $response->body(),
            'result' => (array) ($json['result'] ?? []),
        ];
    }

    protected function tryDeleteWebhookSilently(): void
    {
        $this->callTelegram('deleteWebhook', ['drop_pending_updates' => false]);
    }

    protected function getSetting(string $key, $default = null)
    {
        return DB::table('app_settings')
            ->where('app', self::APP)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    protected function setSetting(string $key, ?string $value): void
    {
        DB::table('app_settings')->updateOrInsert(
            ['app' => self::APP, 'key' => $key],
            ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    protected function maskToken(string $token): string
    {
        if ($token === '') {
            return '';
        }

        if (strlen($token) <= 10) {
            return str_repeat('*', strlen($token));
        }

        return substr($token, 0, 5) . str_repeat('*', max(strlen($token) - 10, 1)) . substr($token, -5);
    }
}

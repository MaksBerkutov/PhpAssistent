<?php

namespace App\Http\Controllers\PublicApi;

use App\Models\Device;
use DevicesReqest;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeviceController extends ApiController
{
    public function index(Request $request)
    {
        $devices = Device::query()
            ->where('user_id', $this->currentUser($request)->id)
            ->latest()
            ->get();

        return $this->success($devices->map(fn (Device $device) => $this->serializeDevice($device))->values());
    }

    public function show(Request $request, int $device)
    {
        return $this->success($this->serializeDevice($this->findOwnedDevice($request, $device)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
        ]);

        try {
            $device = Device::create(array_merge($validated, [
                'user_id' => $this->currentUser($request)->id,
            ], $this->discoverDevice($validated['url'])));
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return $this->success(
            $this->serializeDevice($device),
            'Device created successfully.',
            201
        );
    }

    public function update(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'url' => ['sometimes', 'required', 'string', 'max:255'],
            'refresh_metadata' => ['sometimes', 'boolean'],
        ]);

        $payload = [];

        if (array_key_exists('name', $validated)) {
            $payload['name'] = $validated['name'];
        }

        if (array_key_exists('url', $validated)) {
            $payload['url'] = $validated['url'];
        }

        try {
            if (
                (array_key_exists('url', $validated) && $validated['url'] !== $deviceModel->url) ||
                $request->boolean('refresh_metadata')
            ) {
                $payload = array_merge($payload, $this->discoverDevice($validated['url'] ?? $deviceModel->url));
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        $deviceModel->update($payload);

        return $this->success(
            $this->serializeDevice($deviceModel->fresh()),
            'Device updated successfully.'
        );
    }

    public function destroy(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);
        $deviceModel->delete();

        return $this->success(message: 'Device deleted successfully.');
    }

    public function sendCommand(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);
        $validated = $request->validate([
            'command' => ['required', 'string', 'max:255'],
            'arg' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $response = DevicesReqest::sendReqest($deviceModel->url, $validated['command'], $validated['arg'] ?? null);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return $this->success([
            'device' => $this->serializeDevice($deviceModel),
            'command' => $validated['command'],
            'arg' => $validated['arg'] ?? null,
            'response' => $this->decodePayload($response),
        ]);
    }

    public function configuration(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);

        if (!$deviceModel->configuration) {
            return response()->json([
                'message' => 'This device does not support configuration access.',
            ], 422);
        }

        try {
            $jsonData = DevicesReqest::sendReqest($deviceModel->url, env('GET_CFG_COMMAND', 'SERV_GCFG'));
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return $this->success([
            'device' => $this->serializeDevice($deviceModel),
            'configuration' => $this->decodePayload($jsonData),
            'raw_configuration' => $jsonData,
        ]);
    }

    public function updateConfiguration(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);

        if (!$deviceModel->configuration) {
            return response()->json([
                'message' => 'This device does not support configuration access.',
            ], 422);
        }

        $request->validate([
            'json_data' => ['required_without:jsonData'],
            'jsonData' => ['required_without:json_data'],
        ]);

        $jsonData = $request->input('json_data', $request->input('jsonData'));

        if (is_array($jsonData)) {
            $jsonData = json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        try {
            DevicesReqest::sendReqest($deviceModel->url, env('SET_CFG_COMMAND', 'SERV_SCFG'), (string) $jsonData);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return $this->success(message: 'Device configuration updated successfully.');
    }

    public function firmware(Request $request, int $device)
    {
        $deviceModel = $this->findOwnedDevice($request, $device);

        if (!$deviceModel->ota) {
            return response()->json([
                'message' => 'This device does not support OTA updates.',
            ], 422);
        }

        $request->validate([
            'firmware' => ['required', 'file', 'extensions:bin'],
        ]);

        $path = "public/firmwares/{$deviceModel->user_id}/{$deviceModel->id}";
        Storage::makeDirectory($path);

        $fileName = $request->file('firmware')->getClientOriginalName();
        $request->file('firmware')->storeAs($path, $fileName);

        $downloadUrl = url(Storage::url("$path/$fileName"));

        try {
            $this->sendFirmwareUpdate($deviceModel->url, $downloadUrl);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return $this->success([
            'device' => $this->serializeDevice($deviceModel),
            'firmware_url' => $downloadUrl,
        ], 'Firmware uploaded successfully.');
    }

    private function findOwnedDevice(Request $request, int $deviceId): Device
    {
        $device = Device::query()
            ->where('user_id', $this->currentUser($request)->id)
            ->find($deviceId);

        if (!$device) {
            throw (new ModelNotFoundException())->setModel(Device::class, [$deviceId]);
        }

        return $device;
    }

    private function discoverDevice(string $url): array
    {
        $initCommandName = env('INILIZATION_COMMAND', 'SERV_GAI');
        $initCommand = explode('.', DevicesReqest::sendReqest($url, $initCommandName));

        return [
            'name_board' => array_shift($initCommand),
            'ota' => (bool) array_shift($initCommand),
            'configuration' => (bool) array_shift($initCommand),
            'command' => json_encode($initCommand),
        ];
    }

    private function serializeDevice(Device $device): array
    {
        return [
            'id' => $device->id,
            'name' => $device->name,
            'url' => $device->url,
            'user_id' => $device->user_id,
            'name_board' => $device->name_board,
            'available' => (bool) $device->available,
            'ota' => (bool) $device->ota,
            'configuration' => (bool) $device->configuration,
            'command' => is_array($device->command) ? $device->command : (json_decode($device->command ?? '[]', true) ?? []),
            'created_at' => optional($device->created_at)?->toIso8601String(),
            'updated_at' => optional($device->updated_at)?->toIso8601String(),
        ];
    }

    private function decodePayload(string $payload): mixed
    {
        $decoded = json_decode($payload, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $payload;
    }

    private function sendFirmwareUpdate(string $ip, string $url): void
    {
        $client = new Client();
        $response = $client->post("http://{$ip}/ota", [
            'form_params' => [
                'url' => $url,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            Log::error("Firmware update request failed for device {$ip}.");
            throw new \RuntimeException('Failed to send firmware update request to device.');
        }
    }
}

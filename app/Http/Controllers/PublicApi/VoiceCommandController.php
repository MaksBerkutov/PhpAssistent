<?php

namespace App\Http\Controllers\PublicApi;

use App\Models\Device;
use App\Models\VoiceCommand;
use DevicesReqest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VoiceCommandController extends ApiController
{
    public function index(Request $request)
    {
        $commands = VoiceCommand::query()
            ->where('users_id', $this->currentUser($request)->id)
            ->latest()
            ->get();

        return $this->success($commands->map(fn (VoiceCommand $command) => $this->serializeVoiceCommand($command))->values());
    }

    public function show(Request $request, int $voiceCommand)
    {
        return $this->success($this->serializeVoiceCommand($this->findOwnedVoiceCommand($request, $voiceCommand)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'devices_id' => ['required', 'integer', 'exists:devices,id'],
            'command' => ['required', 'string', 'max:255'],
            'text_trigger' => ['required', 'string', 'max:255'],
            'voice' => ['nullable', 'string', 'max:255'],
        ]);

        $this->findOwnedDevice($request, (int) $validated['devices_id']);

        $voiceCommand = VoiceCommand::create(array_merge($validated, [
            'users_id' => $this->currentUser($request)->id,
        ]));

        return $this->success(
            $this->serializeVoiceCommand($voiceCommand),
            'Voice command created successfully.',
            201
        );
    }

    public function update(Request $request, int $voiceCommand)
    {
        $commandModel = $this->findOwnedVoiceCommand($request, $voiceCommand);
        $validated = $request->validate([
            'devices_id' => ['sometimes', 'required', 'integer', 'exists:devices,id'],
            'command' => ['sometimes', 'required', 'string', 'max:255'],
            'text_trigger' => ['sometimes', 'required', 'string', 'max:255'],
            'voice' => ['nullable', 'string', 'max:255'],
        ]);

        if (array_key_exists('devices_id', $validated)) {
            $this->findOwnedDevice($request, (int) $validated['devices_id']);
        }

        $commandModel->update($validated);

        return $this->success(
            $this->serializeVoiceCommand($commandModel->fresh()),
            'Voice command updated successfully.'
        );
    }

    public function destroy(Request $request, int $voiceCommand)
    {
        $commandModel = $this->findOwnedVoiceCommand($request, $voiceCommand);
        $commandModel->delete();

        return $this->success(message: 'Voice command deleted successfully.');
    }

    public function execute(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'integer'],
            'text_trigger' => ['nullable', 'string', 'max:255'],
            'arg' => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($validated['id']) && empty($validated['text_trigger'])) {
            return response()->json([
                'message' => 'Provide either "id" or "text_trigger".',
            ], 422);
        }

        $query = VoiceCommand::query()->where('users_id', $this->currentUser($request)->id);

        if (!empty($validated['id'])) {
            $query->whereKey($validated['id']);
        } else {
            $query->where('text_trigger', $validated['text_trigger']);
        }

        $commandModel = $query->first();

        if (!$commandModel) {
            throw (new ModelNotFoundException())->setModel(VoiceCommand::class, [$validated['id'] ?? $validated['text_trigger']]);
        }

        $device = $this->findOwnedDevice($request, $commandModel->devices_id);
        DevicesReqest::sendReqest($device->url, $commandModel->command, $validated['arg'] ?? null);

        return $this->success([
            'voice_command' => $this->serializeVoiceCommand($commandModel),
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
            ],
        ], 'Voice command executed successfully.');
    }

    private function findOwnedVoiceCommand(Request $request, int $voiceCommandId): VoiceCommand
    {
        $commandModel = VoiceCommand::query()
            ->where('users_id', $this->currentUser($request)->id)
            ->find($voiceCommandId);

        if (!$commandModel) {
            throw (new ModelNotFoundException())->setModel(VoiceCommand::class, [$voiceCommandId]);
        }

        return $commandModel;
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

    private function serializeVoiceCommand(VoiceCommand $voiceCommand): array
    {
        return [
            'id' => $voiceCommand->id,
            'devices_id' => $voiceCommand->devices_id,
            'users_id' => $voiceCommand->users_id,
            'command' => $voiceCommand->command,
            'text_trigger' => $voiceCommand->text_trigger,
            'voice' => $voiceCommand->voice,
            'created_at' => optional($voiceCommand->created_at)?->toIso8601String(),
            'updated_at' => optional($voiceCommand->updated_at)?->toIso8601String(),
        ];
    }
}

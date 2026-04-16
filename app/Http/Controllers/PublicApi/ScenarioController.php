<?php

namespace App\Http\Controllers\PublicApi;

use App\Models\Device;
use App\Models\Scenario;
use App\Models\ScenarioApi;
use App\Models\ScenarioDb;
use App\Models\ScenarioLog;
use App\Models\ScenarioModule;
use App\Models\ScenarioNotify;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ScenarioController extends ApiController
{
    public function index(Request $request)
    {
        $scenarios = Scenario::query()
            ->where('users_id', $this->currentUser($request)->id)
            ->latest()
            ->get();

        return $this->success($scenarios->map(fn (Scenario $scenario) => $this->serializeScenario($scenario))->values());
    }

    public function show(Request $request, int $scenario)
    {
        return $this->success($this->serializeScenario($this->findOwnedScenario($request, $scenario)));
    }

    public function store(Request $request)
    {
        $validated = $this->validateScenario($request);
        $this->assertScenarioDevices($request, $validated);

        $scenario = new Scenario([
            'users_id' => $this->currentUser($request)->id,
        ]);

        $this->saveScenario($scenario, $validated);

        return $this->success(
            $this->serializeScenario($scenario->fresh()),
            'Scenario created successfully.',
            201
        );
    }

    public function update(Request $request, int $scenario)
    {
        $scenarioModel = $this->findOwnedScenario($request, $scenario);
        $validated = $this->validateScenario($request);
        $this->assertScenarioDevices($request, $validated);

        $this->saveScenario($scenarioModel, $validated);

        return $this->success(
            $this->serializeScenario($scenarioModel->fresh()),
            'Scenario updated successfully.'
        );
    }

    public function destroy(Request $request, int $scenario)
    {
        $scenarioModel = $this->findOwnedScenario($request, $scenario);

        if ($scenarioModel->scenario_logs_id) {
            $scenarioModel->scenarioLog?->delete();
        }

        if ($scenarioModel->scenario_apis_id) {
            $scenarioModel->scenarioApi?->delete();
        }

        if ($scenarioModel->scenario_dbs_id) {
            $scenarioModel->ScenarioDb?->delete();
        }

        if ($scenarioModel->scenario_notifies_id) {
            $scenarioModel->scenarioNotify?->delete();
        }

        if ($scenarioModel->scenario_modules_id) {
            $scenarioModel->scenarioModule?->delete();
        }

        $scenarioModel->delete();

        return $this->success(message: 'Scenario deleted successfully.');
    }

    private function validateScenario(Request $request): array
    {
        return $request->validate([
            'devices_id' => ['required', 'integer', 'exists:devices,id'],
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'actions' => ['required', 'array', 'min:1'],
            'actions.*' => ['string'],
            'log_format' => ['nullable', 'string'],
            'db_login' => ['nullable', 'string'],
            'db_password' => ['nullable', 'string'],
            'db_name' => ['nullable', 'string'],
            'db_table' => ['nullable', 'string'],
            'db_key_field' => ['nullable', 'string'],
            'db_value_field' => ['nullable', 'string'],
            'notification_message' => ['nullable', 'string'],
            'notification_type' => ['nullable', 'string'],
            'change_module' => ['nullable', 'integer', 'exists:devices,id'],
            'change_command' => ['nullable', 'string'],
            'change_arg' => ['nullable', 'string'],
            'api_url' => ['nullable', 'url'],
            'api_body' => ['nullable', 'string'],
        ]);
    }

    private function assertScenarioDevices(Request $request, array $validated): void
    {
        $this->findOwnedDevice($request, (int) $validated['devices_id']);

        if (in_array('change_state', $validated['actions'], true) && !empty($validated['change_module'])) {
            $this->findOwnedDevice($request, (int) $validated['change_module']);
        }
    }

    private function saveScenario(Scenario $scenario, array $validated): void
    {
        $scenarioLogId = $this->syncScenarioChild(
            $scenario->scenario_logs_id,
            $validated['actions'],
            'log',
            ScenarioLog::class,
            fn (ScenarioLog $record) => $record->update(['format' => $validated['log_format'] ?? null]),
            fn () => ScenarioLog::create(['format' => $validated['log_format'] ?? null])
        );

        $scenarioDbId = $this->syncScenarioChild(
            $scenario->scenario_dbs_id,
            $validated['actions'],
            'save_db',
            ScenarioDb::class,
            fn (ScenarioDb $record) => $record->update([
                'login' => $validated['db_login'] ?? null,
                'password' => $validated['db_password'] ?? null,
                'db_name' => $validated['db_name'] ?? null,
                'table_name' => $validated['db_table'] ?? null,
                'name_key' => $validated['db_key_field'] ?? null,
                'name_value' => $validated['db_value_field'] ?? null,
            ]),
            fn () => ScenarioDb::create([
                'login' => $validated['db_login'] ?? null,
                'password' => $validated['db_password'] ?? null,
                'db_name' => $validated['db_name'] ?? null,
                'table_name' => $validated['db_table'] ?? null,
                'name_key' => $validated['db_key_field'] ?? null,
                'name_value' => $validated['db_value_field'] ?? null,
            ])
        );

        $scenarioNotifyId = $this->syncScenarioChild(
            $scenario->scenario_notifies_id,
            $validated['actions'],
            'notify',
            ScenarioNotify::class,
            fn (ScenarioNotify $record) => $record->update([
                'format' => $validated['notification_message'] ?? null,
                'type' => $validated['notification_type'] ?? null,
            ]),
            fn () => ScenarioNotify::create([
                'format' => $validated['notification_message'] ?? null,
                'type' => $validated['notification_type'] ?? null,
            ])
        );

        $scenarioModuleId = $this->syncScenarioChild(
            $scenario->scenario_modules_id,
            $validated['actions'],
            'change_state',
            ScenarioModule::class,
            fn (ScenarioModule $record) => $record->update([
                'devices_id' => $validated['change_module'] ?? null,
                'command' => $validated['change_command'] ?? null,
                'arg' => $validated['change_arg'] ?? null,
            ]),
            fn () => ScenarioModule::create([
                'devices_id' => $validated['change_module'] ?? null,
                'command' => $validated['change_command'] ?? null,
                'arg' => $validated['change_arg'] ?? null,
            ])
        );

        $scenarioApiId = $this->syncScenarioChild(
            $scenario->scenario_apis_id,
            $validated['actions'],
            'send_api',
            ScenarioApi::class,
            fn (ScenarioApi $record) => $record->update([
                'format' => $validated['api_body'] ?? null,
                'url' => $validated['api_url'] ?? null,
            ]),
            fn () => ScenarioApi::create([
                'format' => $validated['api_body'] ?? null,
                'url' => $validated['api_url'] ?? null,
            ])
        );

        $scenario->fill([
            'devices_id' => $validated['devices_id'],
            'key' => $validated['key'],
            'value' => $validated['value'],
            'scenario_logs_id' => $scenarioLogId,
            'scenario_apis_id' => $scenarioApiId,
            'scenario_dbs_id' => $scenarioDbId,
            'scenario_notifies_id' => $scenarioNotifyId,
            'scenario_modules_id' => $scenarioModuleId,
        ]);

        $scenario->save();
    }

    private function syncScenarioChild(?int $currentId, array $actions, string $actionKey, string $class, callable $update, callable $create): ?int
    {
        if (in_array($actionKey, $actions, true)) {
            if ($currentId === null) {
                return $create()->id;
            }

            $update($class::findOrFail($currentId));

            return $currentId;
        }

        if ($currentId !== null) {
            $class::findOrFail($currentId)->delete();
        }

        return null;
    }

    private function findOwnedScenario(Request $request, int $scenarioId): Scenario
    {
        $scenario = Scenario::query()
            ->where('users_id', $this->currentUser($request)->id)
            ->find($scenarioId);

        if (!$scenario) {
            throw (new ModelNotFoundException())->setModel(Scenario::class, [$scenarioId]);
        }

        return $scenario;
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

    private function serializeScenario(Scenario $scenario): array
    {
        $scenario->loadMissing(['device', 'scenarioApi', 'scenarioLog', 'scenarioModule.device', 'scenarioNotify', 'ScenarioDb']);

        $actions = [];

        if ($scenario->scenario_logs_id) {
            $actions[] = 'log';
        }

        if ($scenario->scenario_dbs_id) {
            $actions[] = 'save_db';
        }

        if ($scenario->scenario_notifies_id) {
            $actions[] = 'notify';
        }

        if ($scenario->scenario_modules_id) {
            $actions[] = 'change_state';
        }

        if ($scenario->scenario_apis_id) {
            $actions[] = 'send_api';
        }

        return [
            'id' => $scenario->id,
            'users_id' => $scenario->users_id,
            'devices_id' => $scenario->devices_id,
            'key' => $scenario->key,
            'value' => $scenario->value,
            'actions' => $actions,
            'device' => $scenario->device ? [
                'id' => $scenario->device->id,
                'name' => $scenario->device->name,
            ] : null,
            'log' => $scenario->scenarioLog ? [
                'format' => $scenario->scenarioLog->format,
            ] : null,
            'database' => $scenario->ScenarioDb ? [
                'login' => $scenario->ScenarioDb->login,
                'password' => $scenario->ScenarioDb->password,
                'db_name' => $scenario->ScenarioDb->db_name,
                'table_name' => $scenario->ScenarioDb->table_name,
                'name_key' => $scenario->ScenarioDb->name_key,
                'name_value' => $scenario->ScenarioDb->name_value,
            ] : null,
            'notification' => $scenario->scenarioNotify ? [
                'format' => $scenario->scenarioNotify->format,
                'type' => $scenario->scenarioNotify->type,
            ] : null,
            'module' => $scenario->scenarioModule ? [
                'devices_id' => $scenario->scenarioModule->devices_id,
                'device_name' => $scenario->scenarioModule->device?->name,
                'command' => $scenario->scenarioModule->command,
                'arg' => $scenario->scenarioModule->arg,
            ] : null,
            'api' => $scenario->scenarioApi ? [
                'url' => $scenario->scenarioApi->url,
                'format' => $scenario->scenarioApi->format,
            ] : null,
            'created_at' => optional($scenario->created_at)?->toIso8601String(),
            'updated_at' => optional($scenario->updated_at)?->toIso8601String(),
        ];
    }
}

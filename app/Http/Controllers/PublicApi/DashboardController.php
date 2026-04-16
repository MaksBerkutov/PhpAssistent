<?php

namespace App\Http\Controllers\PublicApi;

use App\Models\Dashboard;
use App\Models\Device;
use App\Models\Widget;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends ApiController
{
    public function index(Request $request)
    {
        $items = Dashboard::query()
            ->where('user_id', $this->currentUser($request)->id)
            ->with(['device', 'widget'])
            ->latest()
            ->get();

        return $this->success($items->map(fn (Dashboard $item) => $this->serializeDashboardItem($item))->values());
    }

    public function show(Request $request, int $dashboard)
    {
        return $this->success($this->serializeDashboardItem($this->findOwnedDashboard($request, $dashboard)));
    }

    public function store(Request $request)
    {
        $validated = $this->validateDashboard($request);
        $device = $this->findOwnedDevice($request, (int) $validated['device_id']);
        $widget = $this->resolveWidget((int) $validated['widget_id'], $validated['access_key'] ?? null);

        $item = Dashboard::create([
            'user_id' => $this->currentUser($request)->id,
            'device_id' => $device->id,
            'widget_id' => $widget->id,
            'command' => $validated['command'],
            'name' => $validated['name'],
            'key' => $validated['key'],
            'values' => json_encode($validated['values'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'argument' => $validated['argument'] ?? null,
        ]);

        return $this->success(
            $this->serializeDashboardItem($item->fresh(['device', 'widget'])),
            'Dashboard item created successfully.',
            201
        );
    }

    public function update(Request $request, int $dashboard)
    {
        $item = $this->findOwnedDashboard($request, $dashboard);
        $validated = $this->validateDashboard($request);
        $device = $this->findOwnedDevice($request, (int) $validated['device_id']);
        $widget = $this->resolveWidget((int) $validated['widget_id'], $validated['access_key'] ?? null);

        $item->update([
            'device_id' => $device->id,
            'widget_id' => $widget->id,
            'command' => $validated['command'],
            'name' => $validated['name'],
            'key' => $validated['key'],
            'values' => json_encode($validated['values'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'argument' => $validated['argument'] ?? null,
        ]);

        return $this->success(
            $this->serializeDashboardItem($item->fresh(['device', 'widget'])),
            'Dashboard item updated successfully.'
        );
    }

    public function destroy(Request $request, int $dashboard)
    {
        $item = $this->findOwnedDashboard($request, $dashboard);
        $item->delete();

        return $this->success(message: 'Dashboard item deleted successfully.');
    }

    private function validateDashboard(Request $request): array
    {
        return $request->validate([
            'device_id' => ['required', 'integer', 'exists:devices,id'],
            'widget_id' => ['required', 'integer', 'exists:widgets,id'],
            'command' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255'],
            'values' => ['required', 'array'],
            'argument' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'access_key' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function findOwnedDashboard(Request $request, int $dashboardId): Dashboard
    {
        $item = Dashboard::query()
            ->where('user_id', $this->currentUser($request)->id)
            ->with(['device', 'widget'])
            ->find($dashboardId);

        if (!$item) {
            throw (new ModelNotFoundException())->setModel(Dashboard::class, [$dashboardId]);
        }

        return $item;
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

    private function resolveWidget(int $widgetId, ?string $accessKey): Widget
    {
        $widget = Widget::query()->findOrFail($widgetId);

        if (!$widget->is_private) {
            return $widget;
        }

        $storedKey = (string) $widget->getRawOriginal('accesses_key');
        $matches = $accessKey !== null && (
            ($storedKey !== '' && Hash::check($accessKey, $storedKey)) ||
            $storedKey === $accessKey
        );

        if (!$matches) {
            abort(403, 'A valid access key is required for this widget.');
        }

        return $widget;
    }

    private function serializeDashboardItem(Dashboard $item): array
    {
        return [
            'id' => $item->id,
            'user_id' => $item->user_id,
            'device_id' => $item->device_id,
            'widget_id' => $item->widget_id,
            'name' => $item->name,
            'command' => $item->command,
            'key' => $item->key,
            'values' => is_array($item->values) ? $item->values : (json_decode($item->values ?? '[]', true) ?? []),
            'argument' => $item->argument,
            'device' => $item->relationLoaded('device') && $item->device ? [
                'id' => $item->device->id,
                'name' => $item->device->name,
                'url' => $item->device->url,
                'available' => (bool) $item->device->available,
            ] : null,
            'widget' => $item->relationLoaded('widget') && $item->widget ? [
                'id' => $item->widget->id,
                'name' => $item->widget->name,
                'widget_name' => $item->widget->widget_name,
                'is_private' => (bool) $item->widget->is_private,
                'version' => $item->widget->version,
                'input_params' => is_array($item->widget->input_params) ? $item->widget->input_params : (json_decode($item->widget->input_params ?? '[]', true) ?? []),
            ] : null,
            'created_at' => optional($item->created_at)?->toIso8601String(),
            'updated_at' => optional($item->updated_at)?->toIso8601String(),
        ];
    }
}

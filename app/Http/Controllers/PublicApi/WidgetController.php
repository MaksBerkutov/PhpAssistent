<?php

namespace App\Http\Controllers\PublicApi;

use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WidgetController extends ApiController
{
    public function index(Request $request)
    {
        $accessKey = $request->query('access_key');

        $publicWidgets = Widget::query()
            ->where('is_private', false)
            ->orderBy('name')
            ->get();

        $privateWidgets = collect();

        if (is_string($accessKey) && $accessKey !== '') {
            $privateWidgets = Widget::query()
                ->where('is_private', true)
                ->orderBy('name')
                ->get()
                ->filter(function (Widget $widget) use ($accessKey) {
                    $storedKey = (string) $widget->getRawOriginal('accesses_key');

                    return ($storedKey !== '' && Hash::check($accessKey, $storedKey)) || $storedKey === $accessKey;
                });
        }

        $widgets = $publicWidgets
            ->concat($privateWidgets)
            ->unique('id')
            ->values();

        return $this->success($widgets->map(fn (Widget $widget) => [
            'id' => $widget->id,
            'name' => $widget->name,
            'widget_name' => $widget->widget_name,
            'input_params' => is_array($widget->input_params) ? $widget->input_params : (json_decode($widget->input_params ?? '[]', true) ?? []),
            'is_private' => (bool) $widget->is_private,
            'version' => $widget->version,
            'created_at' => optional($widget->created_at)?->toIso8601String(),
            'updated_at' => optional($widget->updated_at)?->toIso8601String(),
        ])->values());
    }
}

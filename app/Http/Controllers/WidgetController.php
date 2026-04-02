<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::all();

        return view('Widget.index', compact('widgets'));
    }

    public function create()
    {
        return view('Widget.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'widget_name' => ['required', 'string', 'max:255'],
            'accesses_key' => ['nullable', 'string', 'max:255'],
            'input_params' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_private'] = !$validated['accesses_key'] == null;
        Widget::create($validated);

        return redirect()->route('widget')->with('success', __('ui.widgets.messages.created'));
    }

    public function delete($id)
    {
        if (Auth::user()->role != 'admin') {
            return redirect()->route('home')->with('error', __('ui.widgets.messages.admin_only'));
        }

        Widget::findOrFail($id)->delete();

        return redirect()->route('widget')->with('success', __('ui.widgets.messages.deleted'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('widget')->with('success', __('ui.widgets.messages.updated'));
    }

    public function edit($id)
    {
        return view('Widget.create');
    }

    public function install(Request $request)
    {
        $request->validate([
            'widget' => 'required|file|mimes:zip',
        ]);

        $file = $request->file('widget');

        $tmpDir = storage_path('app/tmp_widget_' . time());
        File::makeDirectory($tmpDir);

        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) === true) {
            $zip->extractTo($tmpDir);
            $zip->close();
        } else {
            File::deleteDirectory($tmpDir);

            return response()->json(['error' => __('ui.widgets.messages.install_unpack_error')], 400);
        }

        $manifestPath = $tmpDir . '/manifest.json';
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($tmpDir);

            return response()->json(['error' => __('ui.widgets.messages.install_manifest_missing')], 400);
        }

        $manifest = json_decode(File::get($manifestPath), true);
        if (!$manifest || !isset($manifest['name'], $manifest['class'], $manifest['version'], $manifest['widget_name'], $manifest['accesses_key'])) {
            File::deleteDirectory($tmpDir);

            return response()->json(['error' => __('ui.widgets.messages.install_manifest_invalid')], 400);
        }

        $name = $manifest['name'];
        $accessesKey = $manifest['accesses_key'];
        $widgetName = $manifest['widget_name'];
        $class = $manifest['class'];
        $version = $manifest['version'];

        $installed = DB::table('widgets')->where('name', $name)->first();
        if ($installed && version_compare($version, $installed->version, '<=')) {
            File::deleteDirectory($tmpDir);

            return response()->json(['error' => __('ui.widgets.messages.install_version_conflict')], 400);
        }

        $targetSrc = app_path('View/Components');
        if (File::isDirectory($tmpDir . '/src')) {
            File::copyDirectory($tmpDir . '/src', $targetSrc);
        }

        $targetViews = resource_path('views/components');
        if (File::isDirectory($tmpDir . '/resources/views')) {
            File::copyDirectory($tmpDir . '/resources/views', $targetViews);
        }

        $targetPublic = public_path('widgets/' . $name);
        if (File::isDirectory($tmpDir . '/public')) {
            File::ensureDirectoryExists($targetPublic);
            File::copyDirectory($tmpDir . '/public', $targetPublic);
        }

        if (!class_exists($class)) {
            File::deleteDirectory($tmpDir);

            return response()->json(['error' => __('ui.widgets.messages.install_class_not_found', ['class' => $class])], 400);
        }

        $schema = [];
        if (method_exists($class, 'getSchema')) {
            $schema = $class::getSchema();
        }

        DB::table('widgets')->updateOrInsert(
            ['name' => $name],
            [
                'name' => $name,
                'widget_name' => $widgetName,
                'accesses_key' => $accessesKey,
                'input_params' => json_encode($schema),
                'updated_at' => now(),
                'created_at' => $installed ? $installed->created_at : now(),
                'is_private' => ($accessesKey !== null),
            ]
        );

        File::deleteDirectory($tmpDir);

        return redirect()->back()->with('success', __('ui.widgets.messages.installed_version', ['name' => $name, 'version' => $version]));
    }
}

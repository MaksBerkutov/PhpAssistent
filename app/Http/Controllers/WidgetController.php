<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return redirect()->route('widget')->with('success ', 'Віджет створен');
    }

    public function delete($id)
    {
        if(Auth::user()->role!="admin") return  redirect()->route("home")->with("error","Вы не админістратор!");
        Widget::findOrFail($id)->delete();
        return redirect()->route('widget')->with('success', 'Віджет успішно видален.');
    }
    public function update(Request $request, $id)
    {
        return redirect()->route('widget')->with('success', 'Сценарій успішно оновлен.');
    }
    public function edit($id)
    {
        return view('Widget.create');
    }
}

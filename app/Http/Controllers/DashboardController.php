<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Device;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $widgets = Dashboard::where('user_id', Auth::id())->get();
        return view('Dashboard.index',compact('widgets'));
    }
    public function create($access = null){
        $widgets = $access==null?Widget::where('is_private', false)->get():
            Widget::where('accesses_key', $access)->get();
        return view('Dashboard.create',compact('widgets'));
    }
    public function add($id){
        $widget = Widget::findOrFail($id);
        $devices = Device::where('user_id', Auth::id())->get();
        return view('Dashboard.add',compact('widget','devices'));
    }
    public function store(Request $request){
        $validate = $request->validate([
            'device_id' => ['required','exists:devices,id'],
            'widget_id' => ['required','exists:widgets,id'],
            'command' => ['required','string'],
            'key' => ['required','string'],
            'values' => ['required','string'],
            'argument' => ['nullable','string'],
            'name' => ['required','string'],
        ]);
        $validate['user_id'] = Auth::id();
        Dashboard::create($validate);
        return redirect()->route('dashboard')->with('success ','Віджет додан');
    }
}

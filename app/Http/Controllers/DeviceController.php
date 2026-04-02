<?php

namespace App\Http\Controllers;

use App\Models\Device;
use DevicesReqest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::where('user_id', Auth::user()->id)->get();

        return view('Devices.index', compact('devices'));
    }

    public function send_command(Request $request)
    {
        $validated = $request->validate([
            'command' => ['required', 'string', 'max:255'],
            'url' => 'required',
            'arg' => ['string', 'nullable', 'max:255'],
        ]);

        $response = DevicesReqest::sendReqest($validated['url'], $validated['command'], $validated['arg']);
        if (str_ends_with($validated['command'], '_REC')) {
            $response = json_decode($response);

            return view('Devices.response', compact('response', 'validated'));
        }

        return redirect()->back()->withInput();
    }

    public function create()
    {
        return view('Devices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'url' => 'required',
        ]);

        try {
            $validated['user_id'] = Auth::id();
            $initCommand = explode('.', DevicesReqest::sendReqest($validated['url'], env('INILIZATION_COMMAND')));
            $validated['name_board'] = array_shift($initCommand);
            $validated['ota'] = array_shift($initCommand);
            $validated['configuration'] = array_shift($initCommand);
            $validated['command'] = json_encode($initCommand);
            Device::create($validated);

            return redirect()->route('devices.create')->with('success', __('ui.devices.messages.added'));
        } catch (\Exception $e) {
            return redirect()->route('devices.create')->with('error', __('ui.devices.messages.add_failed', ['error' => $e->getMessage()]));
        }
    }

    public function upload_firmware(Request $request)
    {
        $request->validate([
            'firmware' => ['required', 'file', 'extensions:bin'],
            'id' => ['required', 'exists:devices,id'],
        ]);

        $device = Device::findOrFail($request['id']);
        if ($device->user_id != Auth::id()) {
            return redirect()->route('devices')->with('error', __('ui.devices.messages.no_access'));
        }

        $path = "public/firmwares/{$device->user_id}/{$device->id}";
        Storage::makeDirectory($path);
        $fileName = $request->file('firmware')->getClientOriginalName();
        $request->file('firmware')->storeAs($path, $fileName);

        $this->sendFirmwareUpdate($device->url, url(Storage::url("$path/$fileName")));

        return redirect()->route('devices')->with('success', __('ui.devices.messages.firmware_uploaded'));
    }

    public function setConfigure(Request $request)
    {
        $validated = $request->validate([
            'jsonData' => ['required', 'string'],
            'id' => ['required', 'exists:devices,id'],
        ]);

        $device = Device::findOrFail($validated['id']);
        if ($device->user_id != Auth::id()) {
            return redirect()->route('devices')->with('error', __('ui.devices.messages.no_access'));
        }

        DevicesReqest::sendReqest($device->url, env('SET_CFG_COMMAND'), $validated['jsonData']);

        return redirect()->route('devices')->with('success', __('ui.devices.messages.config_updated'));
    }

    public function getConfigure(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:devices,id'],
        ]);

        $device = Device::findOrFail($request['id']);
        if ($device->user_id != Auth::id()) {
            return redirect()->route('devices')->with('error', __('ui.devices.messages.no_access'));
        }

        $id = $request['id'];
        $jsonData = DevicesReqest::sendReqest($device->url, env('GET_CFG_COMMAND'));

        return view('Devices.config', compact('jsonData', 'id'));
    }

    protected function sendFirmwareUpdate($ip, $url)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post("http://$ip/ota", [
            'form_params' => [
                'url' => $url,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            Log::info(__('ui.devices.messages.firmware_sent_log', ['ip' => $ip]));

            return;
        }

        Log::error(__('ui.devices.messages.firmware_send_error_log', ['ip' => $ip]));
    }
}

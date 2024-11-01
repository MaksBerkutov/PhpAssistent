<?php

namespace App\Http\Controllers;

use App\Models\Device;
use DevicesReqest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeviceController extends Controller
{
    public function index(){
        $devices = Device::where('user_id',  Auth::user()->id)->get();

        return view('Devices.index',compact('devices'));
    }

    public function send_command(request $request){
        $validated = $request->validate([
            'command' => ['required', 'string', 'max:255'],
            'url' => 'required',
        ]);
        $response = DevicesReqest::sendReqest( $validated["url"],$validated["command"]);
        if(str_ends_with($validated["command"], "_REC")){
            $response = json_decode($response);
            return view('Devices.response',compact('response',"validated"));
        }
        return back();
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
            $validated["user_id"] = Auth::id();
           $initCommand = explode('.',DevicesReqest::sendReqest( $validated["url"],env("INILIZATION_COMMAND")));
            $validated["name_board"] = array_shift($initCommand);
            $validated["command"] = json_encode($initCommand);
            Device::create($validated);
            return redirect()->route('devices.create')->with('success', 'Device added successfully!');
        } catch (\Exception $e) {

            return redirect()->route('devices.create')->with('error', "Failed to add device! {$e->getMessage()}");
        }
    }
    public function upload_firmware(Request $request)
    {
        $request->validate([
            'firmware' => ['required','file','extensions:bin'],
            'id' => ['required','exists:devices,id'],
        ]);
        $device = Device::findOrFail($request['id']);
        if($device->user_id!=Auth::id()){
            return redirect()->route('devices')->with('error', "Вы не имеете доступа к этому модулю!");
        }

        $path = "public/firmwares/{$device->user_id}/{$device->id}";
        Storage::makeDirectory($path);
        $fileName = $request->file('firmware')->getClientOriginalName();
        $request->file('firmware')->storeAs($path, $fileName);
        $this->sendFirmwareUpdate($device->url,url( Storage::url("$path/$fileName")));
        return redirect()->route('devices')->with('success', "Успешно загруженно");

    }
    protected function sendFirmwareUpdate($ip, $url)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post("http://$ip/ota", [
            'form_params' => [
                'url' => $url,
            ],
        ]);

        // Обработка ответа от ESP8266 (если нужно)
        if ($response->getStatusCode() == 200) {
            Log::info('Обновление прошивки успешно отправлено на устройство ' . $ip);
        } else {
            Log::error('Ошибка отправки обновления прошивки на устройство ' . $ip);
        }
    }


}

<?php

namespace App\Http\Controllers;

use DevicesReqest;
use App\Models\Device;
use App\Models\VoiceCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoiceController extends Controller
{
   function index(){
       $commands = VoiceCommand::where('users_id', Auth::id())->get();
       return view('Voice.index',compact('commands'));
   }
   function create(){
       $devices = Device::where('user_id',Auth::id())->get();
       return view('Voice.create',compact('devices'));
   }
   function command(Request $request){
       $validated = $request->validate([
           'devices_id' => 'required|exists:devices,id',
           'command' => 'required|string',
           'arg' => 'nullable|string',

       ]);
       $command = $validated['command'];
       $arg = $validated['arg'];
       Device::where('id', $validated['devices_id'])->where('user_id', Auth::id())->get()->each(function ($device) use ($command, $arg) {
           DevicesReqest::sendReqest($device->url,$command,$arg);
       });

       return response()->json([
           'status' => 'success',
           'message' => 'Данные успешно обработаны',
       ]);
   }
   function store(Request $request){
        $data = $request->validate([
            'devices_id'=>'required|exists:devices,id',
            'command'=>'required|string',
            'text_trigger'=>'required|string',
            'voice' => 'string|nullable',
        ]);
        $data['users_id'] = Auth::id();
        VoiceCommand::create($data);
        return redirect()->back()->with('success','команда добавленна');
   }
   function getAllVoicesCommands(){
       $commands = VoiceCommand::where('users_id', Auth::id())->get();
       $onlyTextCommand = $commands->map(function ($command) {
           return $command->text_trigger;
       });
       return response()->json($onlyTextCommand);
   }
   function Go_Command(Request $request){
       $data = $request->json()->all();

       $command = VoiceCommand::where('users_id', Auth::id())->where('text_trigger',$data['text_trigger'])->get()->first();
       if($command!=null){
           Device::where('id', $command->devices_id)->where('user_id', Auth::id())->get()->each(function ($device) use ($command) {
               DevicesReqest::sendReqest($device->url,$command->command);
           });
       }

   }

}

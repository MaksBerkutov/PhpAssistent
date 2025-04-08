<?php

namespace App\Http\Controllers;
use App\Services\AES as AESService;

use App\Models\Device;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DevicesReqest;
use Symfony\Component\HttpFoundation\Response;

class IOTController extends Controller
{
    public function receive(request $request){
        $jsonData = json_decode($request["message"]);
        $devices = Device::where('name_board', $request["name"])->get();
        foreach ($devices as $device){
            foreach ($jsonData as $key => $value){
                if(is_array($value) || is_object($value)){

                }
                else{
                    $scenarios = Scenario::where('devices_id', $device->id)->where('key', $key)->where('value', $value)->get();
                    foreach ($scenarios as $scenario){
                        $this->ScenarioHandler($scenario,$key,$value);

                    }
                }
            }
        }


    }

    private function ScenarioHandler(Scenario $scenario,string $key,string $value)
    {

        if($scenario->scenarioLog)
            Log::debug($scenario->scenarioLog->format);
            //Log::debug($scenario->scenarioLog->type,str_replace(['{key}', '{value}'], [$key, $value], $scenario->scenarioLog->format));
        if($scenario->scenarioModule){
            DevicesReqest::sendReqest( $scenario->scenarioModule->device->url,$scenario->scenarioModule->command,$scenario->scenarioModule->arg);
        }

        //db,notify,api позже
    }
}

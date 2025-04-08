<?php

namespace App\View\Components;

use DevicesReqest;
use Illuminate\View\Component;
use Exception;

abstract  class Widget extends Component
{
    public string $id;
    public string $device_url;
    public string $device_id;
    public string $command;
    public string $name;
    public string $key;
    public mixed $argument;
    public mixed $value="";
    public bool $isOnline = true;
    protected  function  check_exists(array $fields,array $data):bool
    {
        foreach ($fields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }

        }
        return true;
    }
    public function __construct(string $id,bool $available, string $device_url, string $device_id, string $command, string $key,mixed $argument,string $name )
    {
        $this->id = $id;
        $this->device_url = $device_url;
        $this->device_id = $device_id;
        $this->command = $command;
        $this->name = $name;
        $this->key = $key;
        $this->argument = $argument;
        $this->isOnline = $available;
        if(!$available) return;
        try {
            $response = DevicesReqest::sendReqest( $this->device_url,
                $this->command,$this->argument);
            $responseDecode = json_decode($response);
            if(is_null($responseDecode)){
                throw new Exception("Client offline");
            }
            if(!property_exists($responseDecode,$this->key))
                throw new \Exception("Invalid key");
            $this->value = $responseDecode->{$this->key};

        }
        catch (\Exception $e) {

            $this->isOnline = false;
        }
    }

}

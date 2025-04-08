<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use mysql_xdevapi\Exception;

class WidgetLight extends Widget
{
    /**
     * Create a new component instance.
     */
    public bool $is_light = false;
    public string $command_on ="";
    public string $arg_command_on ="";
    public string $command_off="";
    public string $arg_command_off="";
    public function __construct(string $id,string $available,string $device_url, string $device_id, string $command, string $key,mixed $argument,string $name,mixed $data)
    {
        try {
            parent::__construct($id,$available,$device_url, $device_id, $command, $key,$argument, $name);
            if(!property_exists( $data,'command_on')||
                !property_exists( $data,'command_off')||
                !property_exists( $data,'arg_command_on')||
                !property_exists( $data,'arg_command_off'))
                throw new Exception("No commands");
            $this->command_on = $data->command_on;
            $this->command_off = $data->command_off;
            $this->arg_command_on = $data->arg_command_on;
            $this->arg_command_off = $data->arg_command_off;
            $this->is_light = !($this->value=='true');
        }
        catch (\Exception $e) {
            return redirect()->route('home')->with('error', $e->getMessage());
        }


    }



    public function render(): View|Closure|string
    {
        return view('components.widget-light');
    }
}

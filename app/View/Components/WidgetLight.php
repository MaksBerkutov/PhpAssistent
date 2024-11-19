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
    public string $command_off="";
    public function __construct(bool $available,string $device_url, string $device_id, string $command, string $key,string $name,mixed $data)
    {
        try {
            parent::__construct($available,$device_url, $device_id, $command, $key, $name);
            if(!property_exists( $data,'command_on')||
                !property_exists( $data,'command_off'))
                throw new Exception("No commands");
            $this->command_on = $data->command_on;
            $this->command_off = $data->command_off;
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

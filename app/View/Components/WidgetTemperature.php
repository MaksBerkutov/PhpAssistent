<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WidgetTemperature extends Widget
{
   public string $temperature = "0";
   public string $humidity = "0";
    public function __construct(string $id,bool $available,string $device_url, string $device_id, string $command, string $key,mixed $argument,string $name,mixed $data)
    {
        parent::__construct($id,$available,$device_url, $device_id, $command, $key,$argument, $name);
        if(!$this->isOnline)return;
        $this->temperature = $this->value->Temperature;
        $this->humidity = $this->value->Humidity;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.widget-temperature');
    }
}

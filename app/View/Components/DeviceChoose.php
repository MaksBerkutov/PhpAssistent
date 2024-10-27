<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeviceChoose extends Component
{
    public mixed $devices;
    public string $name;
    public string $label;
    public string $old ='';

    public function __construct(mixed $devices, string $name, string $label, string $old = '')
    {
        $this->devices = $devices;
        $this->name = $name;
        $this->label = $label;
        $this->old = $old;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.device-choose');
    }
}

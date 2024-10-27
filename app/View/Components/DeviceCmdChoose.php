<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeviceCmdChoose extends Component
{
    public string $name;
    public string $deviceChoseName;
    public string $label;

    public string $old ='';
    public function __construct(string $name, string $deviceChoseName,string $label ,string $old = '')
    {
       $this->name = $name;
       $this->deviceChoseName = $deviceChoseName;
       $this->old = $old;
       $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.device-cmd-choose');
    }
}

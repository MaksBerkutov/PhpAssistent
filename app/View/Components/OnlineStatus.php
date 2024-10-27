<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OnlineStatus extends Component
{
    /**
     * Create a new component instance.
     */
    public bool $status;
    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.online-status');
    }
}

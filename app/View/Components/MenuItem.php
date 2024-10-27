<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public string $name;
    public string $href;
    public string $icon;
    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $href, string $icon)
    {
        $this->name = $name;
        $this->href = $href;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-item');
    }
}

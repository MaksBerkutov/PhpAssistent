<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DefaultFormInput extends Component
{
    public string $name;
    public string $type;
    public  string $placeholder="";
    public  string $text="";
    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string $type,  string $placeholder = null,string $text = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->placeholder = $placeholder != null ? $placeholder : ucwords(str_replace('_', ' ', $name));
        $this->text = $text != null ? $text : ucwords(str_replace('_', ' ', $name));

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.default-form-input');
    }
}

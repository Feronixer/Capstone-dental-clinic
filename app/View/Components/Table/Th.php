<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Th extends Component
{
    public $column;
    public $label;
    public $center;

    public function __construct($column, $label, $center = false)
    {
        $this->column = $column;
        $this->label = $label;
        $this->center = $center;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.th');
    }
}

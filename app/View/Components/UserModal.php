<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserModal extends Component
{
    public $id;
    public $title;
    public $action;
    public $method;
    public $user;
    public $roles;
    public function __construct($id, $title, $action, $method, $user = null, $roles = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->user = $user;
        $this->roles = $roles;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-modal');
    }
}

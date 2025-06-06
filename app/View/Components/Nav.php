<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Nav extends Component
{
    // you must go to the config folder and create the nav.php file
    public $items;
    public $active;


    public function __construct()
    {
        $this->items = config('nav', []);
        $this->active = Route::currentRouteName(); // i need to get the current route
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
    }
}

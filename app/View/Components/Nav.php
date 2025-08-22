<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Nav extends Component
{
    // you must go to the config folder and create the nav.php file
    public $items;
    public $active;


    public function __construct($context = 'side')
    {
        // in the first i need check the permission of the user
        $this->items = $this->prepareItem(config('nav'));
        $this->active = Route::currentRouteName(); // i need to get the current route
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.nav');
    }

    protected function prepareItem($items)
    {
        $user = Auth::user();
        // i need to check the permission of the user
        foreach($items as $key => $item) {
            // if the item has permission and the user not have this permission
            if (isset($item['abilitie']) && !$user->can($item['abilitie'])) {
                unset($items[$key]); // remove this item
            }
            return $items;

            // if the item has active and the current route is not active
            // if (isset($item['active']) && !Route::is($item['active'])) {
            //     $items[$key]['active'] = false; // set active to false
            // } else {
            //     $items[$key]['active'] = true; // set active to true
            // }
        }
    }
}

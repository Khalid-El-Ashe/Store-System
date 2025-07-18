<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationsMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public $notifications;
    public $newCount;
    public function __construct($count = 10)
    {
        // i need to read the notification and return to component
        $user = auth()->user();
        $this->notifications = $user->notifications()->take($count)->get(); // the notification is a relation by Laravel created

        // i need get the new notifications
        $this->newCount = $user->unreadNotifications()->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.notifications-menu');
    }
}

<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification // do not forget to add this class in the EventServiceProvider
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        // in here i need to send notification when Order is Created

        $order = $event->order->load('billingAddress'); // i need to load the billingAdress
        // $store = $event->order->store;
        $user = User::where("store_id", $order->store_id)->first(); // i need to get the

        // notify -> هذه تعني انو لمين راح ترسل الاشعار
        if ($order && $user) {
            $user->notify(new OrderCreatedNotification($order));  // in here i need send the notification to user
        }

        // in here i need to send the muliNotifiable notification for the multiUsers by get() collection
        // $users = User::where("store_id", $order->store_id)->get(); // for user collections
        // Notification::send($users, new OrderCreatedNotification($order)); // i need to use the Facad Laravel class
        // foreach ($users as $user) {
        //     // هيك بصير يبعت لكل المستخدمين اشعار
        //     $user->notify(new OrderCreatedNotification($order));
        // }
    }
}

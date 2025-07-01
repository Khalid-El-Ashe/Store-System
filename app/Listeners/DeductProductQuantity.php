<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeductProductQuantity
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
        // i need to get the order from the event class
        $order = $event->order;

        // // update products set quantity = quantity -1
        // foreach ($order->products as $product) {
        //     // the order_item -> is the named us in the pivot table
        //     $product->decrement('quantity', $product->order_item->quantity);
        //     // Product::where("id", '=', $product->product_id)
        //     //     ->update(['quantity' => DB::raw('quantity - ' . $product->quantity)]);
        // }

        // update products set quantity = quantity -1
        foreach ($order->products as $product) {
            $orderedQty = $product->order_item->quantity;
            if ($product->quantity > 0) {
                $product->decrement('quantity', $orderedQty);
            } else {
                Log::warning("الكمية غير كافية للمنتج: {$product->name} (ID: {$product->id})");
            }
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Cart;
use Illuminate\Support\Str;

// php artisan make:observer CartObserver --model=Cart
class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function creating(Cart $cart): void
    {
        # هذا الكود هو لتوليد UUID جديد عند إنشاء عنصر سلة التسوق
        // Generate a new UUID for the cart item
        // and set the cookie_id from the Cart model's static method
        $cart->id = Str::uuid();
        $cart->cookie_id = Cart::getCookieId();
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
        //
    }
}

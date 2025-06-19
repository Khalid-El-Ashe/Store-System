<?php

namespace App\Repositories\Cart;

// this class to make implementation of the CartRepository
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

// this class to can i use the CartRepository interface functions (Implementation functions)
class CartModelRepository implements CartRepository
{
    /**
     * Get all items in the cart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        return Cart::with('product')->where('cookie_id', '=', $this->getCookieId())->get();
    }

    /**
     * Add a product to the cart.
     *
     * @param \App\Models\Product $product
     */
    public function add(Product $product, $quantity = 1)
    {

        // i need to check if the product already exists in the cart, so if is exists, i will update the quantity
        $existingCartItem = Cart::where('product_id', $product->id)
            ->where('cookie_id', '=', $this->getCookieId())
            ->first();

        if (!$existingCartItem) {
            Cart::create([
                'cookie_id' => $this->getCookieId(),
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return $existingCartItem->increment('quantity', $quantity);
    }

    public function update(Product $product, $quantity)
    {
        Cart::where('product_id', $product->id)
            ->where('cookie_id', '=', $this->getCookieId())
            ->update(['quantity' => $quantity]);
    }

    public function delete($id)
    {
        Cart::where('id', $id)
            ->where('cookie_id', '=', $this->getCookieId())
            ->delete();
    }

    public function empty()
    {
        Cart::where('cookie_id', '=', $this->getCookieId())->delete();
    }

    public function total(): float
    {
        return (float) Cart::where('cookie_id', '=', $this->getCookieId())
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->selectRaw('SUM(products.price * carts.quantity) as total')
            ->value('total') ?? 0.0;
    }

    protected function getCookieId(): string
    {
        // this function to get the cookie id
        // if the cookie id is not set, i will make a new one
        $cookieId = Cookie::get('cart_id');

        if (!$cookieId) {
            $cookieId = Str::uuid();
            // set the cookie for 30 days and i need to use the Carbon native php class to use DateTime
            Cookie::queue('cart_id', $cookieId, 60 * 24 * 30);
        }
        return $cookieId;
    }
}

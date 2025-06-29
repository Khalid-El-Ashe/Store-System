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
    protected $items;
    public function __construct()
    {
        $this->items = collect([]);
    }

    /**
     * Get all items in the cart.
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        // in here i need check if the items is emplty add add data in the items
        if (!$this->items->count()) {
            $this->items = Cart::with('product')
                // ->where('cookie_id', '=', $this->getCookieId()) // i need to add this condition into GlobalScop
                ->get();
        }
        return $this->items;
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
            // ->where('cookie_id', '=', $this->getCookieId()) // this line is was Global in Scope Model Class
            ->first();

        if (!$existingCartItem) {
            $cart =  Cart::create([
                // 'cookie_id' => $this->getCookieId(), // i need to add the cookie_id by event in the CartObserve method created
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $this->get()->push($existingCartItem);
            return $cart;
        }
        return $existingCartItem->increment('quantity', $quantity);
    }

    public function update($id, $quantity)
    {
        Cart::where('id', $id)
            // ->where('cookie_id', '=', $this->getCookieId()) // this line is was Global in Scope Model Class
            ->update(['quantity' => $quantity]);
    }

    public function delete($id)
    {
        Cart::where('id', $id)
            // ->where('cookie_id', '=', $this->getCookieId()) // this line is was Global in Scope Model Class
            ->delete();
    }

    public function empty()
    {
        Cart::
            // where('cookie_id', '=', $this->getCookieId())-> // this line is was Global in Scope Model Class
            query()->delete();
    }

    public function total(): float
    {
        // return (float) Cart::
        //     // where('cookie_id', '=', $this->getCookieId()) // this line is was Global in Scope Model Class
        //     join('products', 'carts.product_id', '=', 'products.id')
        //     ->selectRaw('SUM(products.price * carts.quantity) as total')
        //     ->value('total') ?? 0.0;

        return $this->get()->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }
}

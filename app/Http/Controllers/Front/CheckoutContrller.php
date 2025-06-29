<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;

class CheckoutContrller extends Controller
{
    public function create(CartRepository $cart)
    {
        return view('front.checkout', ['cart' => $cart]);
    }

    public function store(Request $request, CartRepository $cart) {
        
    }
}

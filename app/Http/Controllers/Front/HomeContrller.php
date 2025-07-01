<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeContrller extends Controller
{
    public function index(Request $request)
    {
        // i need to use Scope to get the products by Querey active() in the Product model
        $products = Product::with('category')->active()->limit(10)->get();
        // dd($products->count());
        return view('front.home', ['products' => $products]);
    }
}

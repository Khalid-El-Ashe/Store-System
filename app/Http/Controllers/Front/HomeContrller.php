<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class HomeContrller extends Controller
{
    public function index(Request $request)
    {
        // i need to use Scope to get the products by Querey active() in the Product model
        $products = Product::with('category')->active()->limit(10)->get();

        return view('front.home', compact('products'));
    }
}

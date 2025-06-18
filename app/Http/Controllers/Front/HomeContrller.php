<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeContrller extends Controller
{
    public function index(Request $request)
    {
        // i need to use Scope to get the products by Querey
        $products = Product::with('category')->active()->latest()->limit(8)->get();
        return view('front.home', ['products' => $products]);
    }
}

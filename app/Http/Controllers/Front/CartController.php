<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CartController extends Controller
{
    protected $repository;
    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // i need to use my Repository to get the cart items
        return view('front.cart', ['cart' => $this->repository]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $product = Product::findOrFail($request->post('product_id'));
        $this->repository->add($product, $request->post('quantity'));
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);
        $product = Product::findOrFail($request->post('product_id'));
        $this->repository->update($product, $request->post('quantity'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $product = Product::findOrFail($id);
        $this->repository->delete($id);
        // return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully.');
    }
}

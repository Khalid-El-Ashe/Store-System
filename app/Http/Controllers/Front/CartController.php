<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        // i need to use ajax(jQuery) to return the response
        if ($request->exceptJson()) {
            return response()->json(['message' => 'Item added to cart'], Response::HTTP_CREATED);
        } else {
            return response()->json(
                ['message' => 'Item is not added to cart found error'],
                Response::HTTP_BAD_REQUEST
            );
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function show() {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            // 'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        // $product = Product::findOrFail($request->post('product_id'));
        $this->repository->update($id, $request->post('quantity'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $product = Product::findOrFail($id);
        $this->repository->delete($id);

        // this is when use the ajax and jQuery to return response
        return response()->json([
            'message' => 'success to delete item'
        ]);
        // return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully.');
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        // i need to use Scope to get the products by Querey
        $products = Product::with('category')
            ->active()
            ->latest()
            ->paginate(10);

        return view('front.products.index', ['products' => $products]);
    }
    public function show(Product $product)
    {
        // i need to use Scope to get the products by Querey
        // $product = Product::with(['category', 'store', 'tags'])
        //     ->where('slug', $slug)
        //     ->active()
        //     ->firstOrFail();

        if($product->status != 'active') {
            abort(404, 'Product not found');
        }
        return view('front.products.show', ['product' => $product]);
        // dd($product);
        // return view('front.products.show', ['product' => $product]);
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::with('category')
            ->active()
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->paginate(12);

        return view('front.products.search', ['products' => $products, 'query' => $query]);
    }
    public function filter(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'latest') {
                $query->latest();
            }
        }

        $products = $query->paginate(12);

        return view('front.products.filter', ['products' => $products]);
    }
    public function compare(Request $request)
    {
        $productIds = $request->input('products', []);
        $products = Product::with('category')
            ->whereIn('id', $productIds)
            ->active()
            ->get();

        return view('front.products.compare', ['products' => $products]);
    }
    public function wishlist(Request $request)
    {
        // Assuming you have a wishlist functionality implemented
        $wishlist = Product::with('category')
            ->whereIn('id', $request->session()->get('wishlist', []))
            ->active()
            ->get();

        return view('front.products.wishlist', ['wishlist' => $wishlist]);
    }
    public function addToWishlist(Request $request, $id)
    {
        // Assuming you have a wishlist functionality implemented
        $wishlist = $request->session()->get('wishlist', []);
        if (!in_array($id, $wishlist)) {
            $wishlist[] = $id;
            $request->session()->put('wishlist', $wishlist);
        }

        return redirect()->back()->with('success', 'Product added to wishlist.');
    }
    public function removeFromWishlist(Request $request, $id)
    {
        // Assuming you have a wishlist functionality implemented
        $wishlist = $request->session()->get('wishlist', []);
        if (($key = array_search($id, $wishlist)) !== false) {
            unset($wishlist[$key]);
            $request->session()->put('wishlist', $wishlist);
        }

        return redirect()->back()->with('success', 'Product removed from wishlist.');
    }
    public function review(Request $request, $id)
    {
        // Assuming you have a review functionality implemented
        $product = Product::findOrFail($id);
        $review = $request->input('review');

        // Save the review logic here (e.g., save to database)

        return redirect()->route('front.products.show', ['slug' => $product->slug])
            ->with('success', 'Review submitted successfully.');
    }
}

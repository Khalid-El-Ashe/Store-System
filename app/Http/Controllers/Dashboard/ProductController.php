<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        // $products = Product::withoutGlobalScope('store')->paginate();
        $products = Product::with(['category', 'store'])->paginate(10);
        return view('dashboard.products.index', ['products' => $products]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $tags = implode(',', $product->tags()->pluck('name')->toArray());
        return view('dashboard.products.edit', ['product' => $product, 'tags' => $tags]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->except('tags'));

        $tags = json_decode(',', $request->post('tags'));
        $tag_ids = [];

        $saved_tags = Tag::all();
        foreach ($tags as $item) {
            $slug = Str::slug($item->value);
            // $tag = Tag::where('slug', $slug)->first();
            $tag = $saved_tags->where('slug', $slug)->first();

            if (!$tag) {
                $tag = Tag::create([
                    'name' => $item->value,
                    'slug' => $slug
                ]);
            }

            $tag_ids[] = $tag->id;
        }

        $product->tags()->sync($tag_ids);

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        //
    }
}

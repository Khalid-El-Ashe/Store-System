<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\String_;

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
        $tags = $product->tags()->pluck('name')->implode(',');
        return view('dashboard.products.edit', ['product' => $product, 'tags' => $tags]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->except('tags'));
        $tags = array_unique(array_map('trim', explode(',', $request->post('tags'))));
        $tag_ids = [];


        foreach ($tags as $t_name) {
            $slug = Str::slug($t_name);

            $tag = Tag::firstOrCreate(
                ['slug' => $slug],
                ['name' => $t_name]
            );

            $tag_ids[] = $tag->id;
        }

        $product->tags()->sync($tag_ids);

        return redirect()->route('products.index')->with('success', 'Updated is successfully');
    }

    public function destroy(Product $product)
    {
        //
    }
}

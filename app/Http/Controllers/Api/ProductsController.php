<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum')->except('index', 'show'); // هنا اريد ان استثمي بعض الدوال من انو لازم يكون المستحدم او الرابط يحتوي على تحقق من تسجيل الدخول
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // i need using my scop filter function is in Product model class
        // return Product::filter($request->query())->with('category:id,name', 'store:id,name,description', 'tags:id,name')->paginate();

        // todo if you need get the collection DATA
        $products = Product::filter($request->query())->with('category:id,name', 'store:id,name,description', 'tags:id,name')->paginate();
        // return ProductResource::collection($products);
        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation roules
        $request->validate([
            'name'=> 'required|string|max:255',
            'desription'=> 'nullable|string|max:255',
            'category_id'=> 'required|exists:categories,id',
            'status'=> 'in:active,inactive',
            'price'=> 'required|numeric|min:0',
            'compare_price'=> 'nullable|numeric|gt:price'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('products.create')) {
                        return response()->json(['message'=> 'not allowed'], 403);
        } //todo if the user dose not have abillities (صلاحية)

        return Product::create($request->all());
        // return Response::json(Product::create($request->all()), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // return $product;

        return new ProductResource($product); // todo i make my collection is from Resource

        // return Product::findOrFail($product)->load('category:id,name', 'store:id,name', 'tags:id,name');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
         // validation roules
        $request->validate([
            'name'=> 'sometimes|string|max:255', // todo you can see i using the sometimes roule
            'desription'=> 'nullable|string|max:255',
            'category_id'=> 'sometimes|exists:categories,id',
            'status'=> 'in:active,inactive',
            'price'=> 'sometimes|numeric|min:0',
            'compare_price'=> 'nullable|numeric|gt:price'
        ]);

        $user = $request->user();
            if (!$user->tokenCan('products.update')) {
                       return response()->json(['message'=> 'not allowed'], 403);
        } //todo if the user dose not have abillities (صلاحية)


        // return $product->update($request->all());
        $product->update($request->all());
        return Response::json($product, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = Auth::guard('sanctum')->user();
            if (!$user->tokenCan('products.delete')) {
            return response()->json(['message'=> 'not allowed'], 403);
        } //todo if the user dose not have abillities (صلاحية)

        Product::destroy($id);
        return response()->json(['Deleted Product is successfully', 200]);
    }
}

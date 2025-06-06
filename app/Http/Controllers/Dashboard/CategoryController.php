<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // this function for the policy
    // طبعا هاي الدالة على مستوى الكلاس كله بدون الحاجة للذهاب لكل دالة ووضع فيها الصلاحية
    public function __construct()
    {
        // $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        $categories = Category::all();
        // i need to use the pagination
        // $categories = Category::paginate(10);

        return view('dashboard.categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        return view('dashboard.categories.create', ['parents' => $parents]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // DB::insert('insert into categories (name, slug, description, image, status, parent_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [
        //     'Phones',
        //     '1597532',
        //     null,
        //     null,
        //     'active',
        //     null,
        //     now(),
        //     now()
        // ]);

        // DB::create([
        //     'name' => 'require|string|min:8|max:100',
        //     'description' => 'require|string|min:10|max:255',
        //     'status' => 'require|in:active,archived'
        //.....
        // ]);

        // code the DB Builder

        // $path = null;
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('uploads', 'public');
        // }
        // DB::table('categories')->insert([
        //     'name'        => $request->get('name'),
        //     'slug'        => Str::slug($request->post('name')),
        //     'description' => $request->get('description'),
        //     'image'       => $path,
        //     'status'      => $request->get('status') ?: null,
        //     'parent_id'   => $request->get('parent_id'),
        //     'created_at'  => now(),
        //     'updated_at'  => now(),
        // ]);


        // // code Elequent ORM Database
        // $path = null;
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('uploads', 'public');
        // }
        // Category::create([
        //     'name'        => $request->get('name'),
        //     'slug'        => Str::slug($request->post('name')),
        //     'description' => $request->get('description'),
        //     'image'       => $path,
        //     'status'      => $request->get('status') ?: null,
        //     'parent_id'   => $request->get('parent_id'),
        // ]);

        // in here you can to write your validations
        // $request->validate([
        //     'name' => 'required|string|min:5|max:100',
        //     'slug' => Str::slug($request->post('name')),
        //     'description' => 'nullable|string|min:20|max:255',
        //     'image' => 'mimes:jpeg,jpg,png,gif|max:1000',
        //     'status' => 'require|in:active,archived',
        //     'parent_id' => 'nullable|int|exists:categories,id',
        // ]);

        $request->validate(Category::validation($request));
        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);
        $data = $request->except('image');
        $data['image'] = $this->uploadFile($request, 'image');

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Created Category is successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Category $categories,
    public function edit(string $id)
    {

        try {
            $category = Category::find($id);
        } catch (Exception $e) {
            abort(404);
        }
        // Elequent Builder Database

        // $parents = Category::where('id', '!=', $id)->get(); // select * from categories where id != $id

        // if you have more wheres query use Clager function or callBack
        // " select * from `categories` where `id` != ? and (`parent_id` is null or `parent_id` != ?) "
        $parents = Category::where('id', '!=', $id)->where(function ($query) use ($id) {
            $query->whereNull('parent_id')
                ->orWhere('parent_id', '!=', $id);
        })->get();

        return view('dashboard.categories.edit', ['category' => $category, 'parents' => $parents]);


        // $parents = $categories->all();
        // return view('dashboard.categories.edit', ['categories' => $categories, 'parents' => $parents]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     'name' => "required|string|min:5|max:100|unique:categories,name,$id",
        //     'description' => 'nullable|string|min:20|max:255',
        //     'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000',
        //     'status' => 'required|in:active,archived',
        //     'parent_id' => 'nullable|int|exists:categories,id',
        // ]);

        $request->validate(Category::validation($request, $id));
        $category = Category::findOrFail($id);

        $old_image = $category->image;
        $data = $request->except('image');

        // $data['image'] = $this->uploadFile($request, 'image'); // i build this function uploadImage from Controller Class

        if ($request->hasFile('image')) {
            $path = $this->uploadFile($request, 'image');
            $data['image'] = $path;

            if ($old_image) {
                Storage::disk('public')->delete($old_image);
            }
        }
        $category->update($data);

        // $category->fill($request->all())->save();
        return redirect()->route('categories.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Category::where('id', '=', $id)->delete();
        $category = Category::findOrFail($id);
        $category->delete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('categories.index');
    }
}

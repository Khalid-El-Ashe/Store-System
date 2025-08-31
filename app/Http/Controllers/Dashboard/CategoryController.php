<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


class CategoryController extends Controller
{
    public function index()
    {

        // if this user have not this permission
        // if(Gate::denies('categories.view')) {
        //     abort(403, 'You do not have permission to view categories.');
        // }

        // if this user have this permission
        if (!Gate::allows('categories.view')) {
            abort(403);
        }

        $request = request();
        // $categories = Category::paginate(2);

        // get the parent name by join


        // i need to use my Scope Model
        $categories = Category::with('parent')
            // leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            //     ->select([
            //         'categories.*',
            //         'parents.name as parent_name'
            //     ])
            // ->select('categories.*')
            // ->selectRaw('(select count(*) from products where category_id = categories.id) as products_count')
            // ->select(DB::raw('(select count(*) from products where category_id = category.id) as products_count'))
            ->withCount('products')
            ->withCount(['products as products_number' => function ($query) {
                // (select count(*) from products where and status = 'active' and category_id = categories.id) as products_count
                $query->where('status', '=', 'active');
            }])
            ->filter($request->query())
            ->latest() // طبعا هان تريب وفلترة الاحدث فالاقدم وممكن التخصيص حسب الحقل
            // ->withTrashed()
            // ->onlyTrashed()
            ->paginate(10);

        // $categories = Category::onlyTrashed(); // get the categories just is trashed
        // $categories = Category::withTrashed(); // get the categories and get the trashed
        return view('dashboard.categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // if this user do not have this permission
        if (!Gate::allows('categories.create')) {
            return view('error');
        }

        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {

        // if this user have this permission
        Gate::authorize('categories.create');

        // $request->validate(Category::validation($request));
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
    public function show(Category $category)
    {
        if (Gate::denies('categories.view')) {
            abort(403, 'You do not have permission to view this category.');
        }
        return view('dashboard.categories.show', ['category' => $category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Category $categories,
    public function edit(string $id)
    {
        Gate::authorize('categories.update');

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

        return view('dashboard.categories.edit', compact('category', 'parents'));


        // $parents = $categories->all();
        // return view('dashboard.categories.edit', ['categories' => $categories, 'parents' => $parents]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        // Gate::authorize('categories.update');

        // $request->validate(Category::validation($request, $id));
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
        return redirect()->route('dashboard.categories..index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Gate::authorize('categories.delete');

        // Category::where('id', '=', $id)->delete();
        // $category = Category::findOrFail($id);
        $category->delete();

        // if ($category->image) {
        //     Storage::disk('public')->delete($category->image);
        // }

        return redirect()->route('dashboard.categories.index');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(2);
        return view('dashboard.categories.trash', ['categories' => $categories]);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id); // i need to find the $id from the trashed categories
        if ($category) {
            Category::restore();
        }
        return redirect()->route('dashboard.categories.trash')->with('success', 'Category is Restored');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id); // i need to find the $id from the trashed categories
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        return redirect()->route('dashboard.categories.trash')->with('success', 'Category is Deleted');
    }
}

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
    public function index()
    {
        $request = request();
        // $categories = Category::paginate(2);

        // get the parent name by join


        // i need to use my Scope Model
        $categories = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
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
        $parents = Category::all();
        return view('dashboard.categories.create', ['parents' => $parents]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
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
    public function update(CategoryRequest $request, string $id)
    {
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

        // if ($category->image) {
        //     Storage::disk('public')->delete($category->image);
        // }

        return redirect()->route('categories.index');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(2);
        return view('dashboard.categories.trash', ['categories' => $categories]);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id); // i need to find the $id from the trashed categories
        $category->restore();
        return redirect()->route('categories.trash')->with('success', 'Category is Restored');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id); // i need to find the $id from the trashed categories
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        return redirect()->route('categories.trash')->with('success', 'Category is Deleted');
    }
}

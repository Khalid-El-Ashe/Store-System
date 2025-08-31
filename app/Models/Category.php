<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class Category extends Model
{
    use HasFactory; //SoftDeletes; // using the SoftDeletes trait

    // this is to protected the elements
    protected $fillable = ['name', 'slug', 'description', 'image', 'status', 'parent_id', 'created_at', 'updated_at'];
    // and if you need to protect the elements and you have mores you can to use guarded
    // protected $guarded = []; // you do not need to add anything this array brackets


    // the Category has many Products so i need to make relation
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault(['name' => 'no parent']);
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function scopeStatus(Builder $builder, $status)
    {
        $builder->where('status', '=', $status);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('categories.name', 'like', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('categories.status', '=', $value);
        });
    }

    // // i need to make this function to validate the elements
    // public static function validation(Request $request, $id = 0)
    // {
    //     return [
    //         'name' => "required|string|min:5|max:100|unique:categories,name,$id", // unique:categories,name if the database have same name do not save it and send message to user
    //         'description' => 'nullable|string|min:20|max:255',
    //         'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000',
    //         'status' => 'required|in:active,archived',
    //         'parent_id' => 'nullable|int|exists:categories,id',
    //     ];
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class Category extends Model
{
    use HasFactory;

    // this is to protected the elements
    protected $fillable = ['name', 'slug', 'description', 'image', 'status', 'parent_id', 'created_at', 'updated_at'];
    // and if you need to protect the elements and you have mores you can to use guarded
    // protected $guarded = []; // you do not need to add anything this array brackets

    // i need to make this function to validate the elements
    public static function validation(Request $request, $id = 0)
    {
        return [
            'name' => "required|string|min:5|max:100|unique:categories,name,$id", // unique:categories,name if the database have same name do not save it and send message to user
            'description' => 'nullable|string|min:20|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000',
            'status' => 'required|in:active,archived',
            'parent_id' => 'nullable|int|exists:categories,id',
        ];
    }
}

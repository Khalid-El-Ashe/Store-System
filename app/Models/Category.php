<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // this is to protected the elements
    protected $fillable = ['name', 'slug', 'description', 'image', 'status', 'parent_id', 'created_at', 'updated_at'];
    // and if you need to protect the elements and you have mores you can to use guarded
    // protected $guarded = []; // you do not need to add anything this array brackets
}

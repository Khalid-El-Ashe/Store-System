<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // the Product model hasOne Category model i need to make this relation
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function store() {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    protected static function booted()
    {
        // in here i need to make and use Global Scope
        // static::addGlobalScope('store', function (Builder $builder) {
        //     $user = Auth::user();
        //     if ($user->sotre_id) {
        //         $builder->where('store_id', '=', $user->store_id);
        //     }
        // });
        static::addGlobalScope(StoreScope::class);
    }
}

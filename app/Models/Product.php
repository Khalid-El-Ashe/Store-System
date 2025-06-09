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

    protected $fillable = [
        'name',
        'category_id',
        'store_id',
        'slug',
        'description',
        'image',
        'price',
        'compare_price',
        'options',
        'rating',
        'featured',
        'status',
    ];

    // the Product model hasOne Category model i need to make this relation
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    // i need to make relastions many to many
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class, // Related Model
            'product_tag', // Pivot Table
            'product_id', // forignId
            'tag_id', // forignId
            'id', // primary Key for the first model current model
            'id' // primary Key for the second model related model
        );
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

<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use Illuminate\Support\Str;

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

    // i need to make a scope to get the active products
    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        $query->where('status', '=', 'active');
    }

    // accessor for the image attribute this is a scope function
    /**
     * Get the URL of the product image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return "https://trukszyn.pl/wp-content/uploads/woocommerce-placeholder-348x348.png";
        }
        if (Str::startsWith($this->image, 'http')) {
            return $this->image; // If the URL is already absolute, return it as is
        }

        return asset('storage/' . $this->image);
    }

    // i need to make scope function to get the compare_price
    /**
     * Scope a query to only include products with a compare price.
     */
    public function getSalePercentAttrubute()
    {
        if (!$this->compare_price) {
            return 0; // If no have compare_price
        }
        return number_format(100 - (100 * $this->compare_price / $this->price), 2);
        // return $query->whereNotNull('compare_price')->where('compare_price', '>', 0);
    }
}

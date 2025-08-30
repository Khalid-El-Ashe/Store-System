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


    // todo i need make this proberties to hidden the value is not need it
    protected $hidden = [
        'image',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // i need make accessure attribute to use in api
    protected $appends = ['image_url'];

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

        static::creating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });
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
        $query->where('status', 'LIKE', 'active');
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

    // i need make this local scop to using in api
    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([ // todo searching about array_merge
            'store_id' => null,
            'category_id' => null,
            'tag_id' => null,
            'status' => 'active',
        ], $filters);

        $builder->when($options['status'], function ($query, $status) {
            return $query->where('status', $status);
        });

        $builder->when($options['store_id'], function ($builder, $value) {
            $builder->where('store_id', $value);
        });

        $builder->when($options['category_id'], function ($builder, $value) {
            $builder->where('category_id', $value);
        });

        $builder->when($options['tag_id'], function ($builder, $value) {

            // todo native SQL command
            // $builder->whereRaw('id in (select product_id from product_tag where tag_id = ?)', [$value]);
            // $builder->whereRaw('iexists in (select 1 from product_tag where tag_id = ? and product_id = products.id)', [$value]);

            // todo same native SQL command by Elequent DB (Query Builder)
            $builder->whereExists(function ($query) use ($value) {
                $query->select(1)->from('product_tag')->whereRow('product_id = products.id')->where('tag_id', $value);
            });

            // $builder->whereHas('tags', function ($builder) use ($value) {
            //     $builder->where('id', $value);
            // });
        });
    }
}

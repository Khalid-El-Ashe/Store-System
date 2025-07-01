<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "user_id",
        "payment_method",
        "status",
        "payment_status"
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest Customer'
        ]);
    }
    // the relation is manyToMany
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id', 'id', 'id')
            ->using(OrderItem::class) // عشان عنا جدول وسيط لازم استخدم المودل الخاص بالجدول الوسيط في العلاقة
            ->as('order_item') // i need to make named to the pivot table to response
            ->withPivot([
                // هان اقوم بتحديد الجداول التي اريد ان ااتي بها من الجدول الوسيط
                // i need to get the columnes need return it
                'product_name',
                'price',
                'quantity',
                'options'
            ]);
    }

    // One-To-Many Relation
    public function addresses()
    {
        // return $this->addresses()->where('type', '=', 'shipping'); // in here i return a collection data
        return $this->hasMany(OrderAddress::class);
    }

    // طبعا العلاقة هان واحد لواحد
    // this function to return not all addresses return Address needed
    // هذه الدالة ما بترجع كل العناوين فقط بترجع عنوان معين
    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')->where('type', '=', 'billing');
    }
    public function shippingAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'id')->where('type', '=', 'shipping');
    }

    protected static function booted()
    {
        static::creating(function (Order $order) {
            $order->number = Order::getNextOrderNumber(); // i need to get the year is like this 20240001, 20250002
        });
    }

    public static function getNextOrderNumber()
    {
        $year = Carbon::now()->year;
        $number = Order::whereYear('created_at', $year)->max('number'); // select max(number) from orders
        if ($number) {
            return $number + 1;
        }
        return $year . '0001';
    }
}

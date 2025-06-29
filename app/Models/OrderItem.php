<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot // this model i a model PivotTable so i need to extends the Pivot Model type
{
    use HasFactory;
    protected $table = 'order_items';
    public $incrementing = true;

    // One-To-One Relation
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name' => $this->product_name
        ]);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

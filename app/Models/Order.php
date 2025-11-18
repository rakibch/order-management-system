<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'order_number','user_id','vendor_id','status','subtotal','shipping','tax','total',
        'billing_address','shipping_address','meta','placed_at','processed_at'
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'meta' => 'array',
        'placed_at' => 'datetime',
        'processed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

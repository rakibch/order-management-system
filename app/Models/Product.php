<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku','name','description','vendor_id','active','price','attributes'
    ];

    protected $casts = [
        'attributes' => 'array',
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // helper to return effective price (if no variants)
    public function getEffectivePriceAttribute()
    {
        if ($this->variants()->exists()) {
            return $this->variants()->orderBy('price')->first()->price;
        }
        return $this->price;
    }
}

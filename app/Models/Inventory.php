<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_variant_id','stock','reserved','low_stock_threshold','location'
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function isLow()
    {
        return $this->stock <= $this->low_stock_threshold;
    }
}

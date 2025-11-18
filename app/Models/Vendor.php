<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name','user_id','email','phone','meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

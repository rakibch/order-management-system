<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
     protected $model = \App\Models\ProductImage::class;

    public function definition()
    {
        return [
            'path' => 'products/' . Str::uuid() . '.jpg',
            'alt' => $this->faker->word,
            'sort_order' => 0,
            'is_primary' => false,
        ];
    }
}

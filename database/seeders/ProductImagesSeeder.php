<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::all()->each(function ($product) {
            ProductImage::factory()->count(3)->create([
                'product_id' => $product->id,
            ])->each(function ($image, $key) {
                $image->update(['is_primary' => $key === 0]); // first image is primary
            });
        });
    }
}

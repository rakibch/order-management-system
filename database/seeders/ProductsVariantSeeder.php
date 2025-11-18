<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
    
class ProductsVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::all()->each(function ($product) {
            ProductVariant::factory(3)->create([
                'product_id' => $product->id,
                'price' => $product->price ?? rand(50, 500),
            ]);
        });
    }
}

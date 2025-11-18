<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Inventory;

class InventoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductVariant::all()->each(function ($variant) {
            Inventory::factory()->create([
                'product_variant_id' => $variant->id,
                'stock' => rand(10, 100),
            ]);
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\OrderItem;

class OrdreItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::all()->each(function ($order) {
            $variants = ProductVariant::inRandomOrder()->take(rand(1, 3))->get();
            foreach ($variants as $variant) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name,
                    'unit_price' => $variant->price,
                    'quantity' => rand(1, 5),
                    'total_price' => $variant->price * rand(1, 5),
                ]);
            }
        });
    }
}

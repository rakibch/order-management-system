<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = \App\Models\OrderItem::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
            'unit_price' => $this->faker->randomFloat(2, 10, 500),
            'quantity' => $this->faker->numberBetween(1,5),
            'total_price' => 0,
            'meta' => json_encode([]),
        ];
    }
}

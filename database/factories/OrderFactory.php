<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition()
    {
        return [
            'order_number' => strtoupper($this->faker->unique()->bothify('ORD-####')),
            'status' => 'pending',
            'subtotal' => 0,
            'shipping' => 0,
            'tax' => 0,
            'total' => 0,
            'billing_address' => json_encode(['address' => $this->faker->address]),
            'shipping_address' => json_encode(['address' => $this->faker->address]),
            'placed_at' => now(),
        ];
    }
}

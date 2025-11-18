<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    protected $model = \App\Models\Inventory::class;

    public function definition()
    {
        return [
            'stock' => $this->faker->numberBetween(10, 100),
            'reserved' => 0,
            'low_stock_threshold' => 5,
            'location' => $this->faker->city,
        ];
    }
}

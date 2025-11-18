<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
     protected $model = \App\Models\Product::class;

    public function definition()
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('PROD-####')),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'active' => true,
            'attributes' => json_encode(['color' => $this->faker->colorName, 'size' => 'M']),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = \App\Models\ProductVariant::class;

    public function definition()
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('VAR-####')),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'attributes' => json_encode(['color' => $this->faker->colorName, 'size' => 'L']),
            'active' => true,
        ];
    }
}

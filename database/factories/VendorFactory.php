<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = \App\Models\Vendor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'meta' => json_encode(['info' => $this->faker->sentence]),
        ];
    }
}

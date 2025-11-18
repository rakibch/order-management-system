<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make('password'),
        'role' => 'customer',
        ];
    }

    public function admin()
    {
        return $this->state(['role' => 'admin']);
    }

    public function vendor()
    {
        return $this->state(['role' => 'vendor']);
    }
}

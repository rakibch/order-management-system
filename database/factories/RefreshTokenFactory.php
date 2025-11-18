<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RefreshToken>
 */
class RefreshTokenFactory extends Factory
{
    protected $model = \App\Models\RefreshToken::class;

    public function definition()
    {
        return [
            'token_hash' => hash('sha256', Str::random(64)),
            'device' => 'web-' . Str::random(6),
            'ip' => '127.0.0.1',
            'expires_at' => Carbon::now()->addDays(30),
        ];
    }
}

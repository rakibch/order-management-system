<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RefreshToken;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RefreshTokensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            RefreshToken::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;  

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $vendors = User::where('role', 'vendor')->get();

        foreach ($vendors as $user) {
            Vendor::factory()->create([
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name . ' Store',
            ]);
        }
    }
}

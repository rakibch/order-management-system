<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $customers = User::where('role', 'customer')->get();
        $vendors = Vendor::all();

        foreach ($customers as $customer) {
            $vendor = $vendors->random();
            Order::factory()->create([
                'user_id' => $customer->id,
                'vendor_id' => $vendor->id,
            ]);
        }
    }
}

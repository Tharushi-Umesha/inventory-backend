<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::insert([
            [
                'product_id'  => 1,
                'quantity'    => 2,
                'total_price' => 59.98,
                'status'      => 'completed',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'product_id'  => 2,
                'quantity'    => 1,
                'total_price' => 89.99,
                'status'      => 'pending',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Wireless Mouse X200',
                'sku' => 'WMX200',
                'category' => 'Accessories',
                'quantity' => 50,
                'price' => 29.99,
                'description' => 'High-precision wireless mouse with ergonomic design',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mechanical Keyboard K500',
                'sku' => 'MK500',
                'category' => 'Accessories',
                'quantity' => 20,
                'price' => 89.99,
                'description' => 'RGB backlit mechanical keyboard with blue switches',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

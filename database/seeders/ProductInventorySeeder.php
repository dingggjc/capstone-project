<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products;

class ProductInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Products::insert([
            [
                'product_name' => 'Smartphone',
                'product_description' => 'High-quality smartphone',
                'product_price' => 999.99,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Office Chair',
                'product_description' => 'Ergonomic office chair',
                'product_price' => 149.99,
                'product_quantity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Headphones',
                'product_description' => 'Wireless headphones',
                'product_price' => 59.99,
                'product_quantity' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
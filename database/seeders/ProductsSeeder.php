<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run()
    {

        Products::create([
            'product_name' => 'Brewed Coffee Car Freshener',
            'product_description' => 'Freshener for cars.',
            'product_price' => 120,
            'product_quantity' => 50,
        ]);

        Products::create([
            'product_name' => 'Pepper Mint Car Freshener',
            'product_description' => 'Freshener for cars.',
            'product_price' => 120,
            'product_quantity' => 50,
        ]);

        Products::create([
            'product_name' => 'Gardenside armor coat/ armor all',
            'product_description' => 'Coating and Armor',
            'product_price' => 120,
            'product_quantity' => 50,
        ]);
    }
}

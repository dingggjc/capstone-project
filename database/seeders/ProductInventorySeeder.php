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
                'product_name' => 'Foam Shampoo',
                'product_description' => "Specialized cleaning product designed to provide a thick, rich foam that effectively lifts and encapsulates dirt, grime, and contaminants from a vehicle's surface.",
                'product_price' => 1500,
                'product_quantity' => 3785,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Rockstar Anti Bacterial',
                'product_description' => 'Antibacterial and antifungal agent',
                'product_price' => 350,
                'product_quantity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Rockstar Anti Scratch',
                'product_description' => 'Remove scratches from the surface of the car',
                'product_price' => 350,
                'product_quantity' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Cold Brew',
                'product_description' => 'A refreshing scent reminiscent of cold brew coffee.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Fresh Lavender',
                'product_description' => 'Calming and soothing lavender scent.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Ocean Freezer',
                'product_description' => 'Cool and fresh ocean breeze fragrance.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Fresh Bamboo',
                'product_description' => 'Crisp and clean bamboo scent.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Baby Powder',
                'product_description' => 'Soft and nostalgic baby powder aroma.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Cucumber Melon',
                'product_description' => 'Refreshing cucumber blended with sweet melon.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Brewed Coffee',
                'product_description' => 'Rich and aromatic brewed coffee fragrance.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Marine Squash',
                'product_description' => 'Vibrant and aquatic marine squash aroma.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Irish Spring',
                'product_description' => 'Fresh and clean Irish spring scent.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Lemon Squash',
                'product_description' => 'Bright and zesty lemon squash fragrance.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Sweet Cherry',
                'product_description' => 'Juicy and sweet cherry scent.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Strawberry Ice Cream',
                'product_description' => 'Creamy and fruity strawberry ice cream aroma.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Peppermint',
                'product_description' => 'Refreshing and cool peppermint scent.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Juicy Citrus',
                'product_description' => 'Lively and tangy citrus fragrance.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'Clinique Happy Men',
                'product_description' => 'Energetic and uplifting fragrance for men.',
                'product_price' => 250,
                'product_quantity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
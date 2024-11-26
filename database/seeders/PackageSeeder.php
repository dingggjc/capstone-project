<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageModel;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PackageModel::insert([
            [
                'package_name' => 'Regular (Package 1)',
                'package_description' => 'Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 200.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Regular (Package 1)',
                'package_description' => 'Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 250.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Regular (Package 1)',
                'package_description' => 'Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 300.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Complete (Package 2)',
                'package_description' => 'Gardenside Armor Coat, Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 280.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Complete (Package 2)',
                'package_description' => 'Gardenside Armor Coat, Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 330.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Complete (Package 2)',
                'package_description' => 'Gardenside Armor Coat, Body Wash with Foam Shampoo, Tire Black, Vacuum',
                'package_price' => 430.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Premium (Package 3)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Tire Black, Under Wash',
                'package_price' => 500.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Premium (Package 3)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Tire Black, Under Wash',
                'package_price' => 650.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Premium (Package 3)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Tire Black, Under Wash',
                'package_price' => 800.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Super Premium (Package 4)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 1100.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Super Premium (Package 4)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 1200.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Super Premium (Package 4)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 1350.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Motorcycle (Other Services)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 100.00,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Big Bikes (Other Services)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 150.00,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'RE/TVS/Piaggio (Other Services)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 150.00,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'package_name' => 'Multicab (Other Services)',
                'package_description' => 'Body Wax, Gardenside Armor Coat, Foam Shampoo, Vacuum, Engine Wash, Under Wash',
                'package_price' => 180.00,
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
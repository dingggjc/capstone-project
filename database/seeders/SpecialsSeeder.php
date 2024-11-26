<?php

namespace Database\Seeders;

use App\Models\specialsModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        specialsModel::insert([
            [
                'name' => 'Gardenside armor coat',
                'description' => ' (BODY)',
                'price' => 150.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardenside armor coat',
                'description' => ' (BODY)',
                'price' => 200.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardenside armor coat',
                'description' => ' (BODY)',
                'price' => 250.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Gardenside Armor Coat',
                'description' => ' (DASHBOARD/FINDER MATTE)',
                'price' => 100.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardenside Armor Coat',
                'description' => ' (DASHBOARD/FINDER MATTE)',
                'price' => 100.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gardenside Armor Coat',
                'description' => ' (DASHBOARD/FINDER MATTE)',
                'price' => 150.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Underwash',
                'description' => ' (UNDERCHASIS)',
                'price' => 120.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Underwash',
                'description' => ' (UNDERCHASIS)',
                'price' => 150.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Underwash',
                'description' => ' (UNDERCHASIS)',
                'price' => 150.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Armour All',
                'description' => '(Armour All)',
                'price' => 100.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Armour All',
                'description' => '(Armour All)',
                'price' => 100.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Armour All',
                'description' => '(Armour All)',
                'price' => 150.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Watermarks / Acid Rain Removal',
                'description' => '',
                'price' => 300.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Watermarks / Acid Rain Removal',
                'description' => '',
                'price' => 350.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Watermarks / Acid Rain Removal',
                'description' => '',
                'price' => 350.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Back to Zero',
                'description' => '(Disinfect & Odor Elimination)',
                'price' => 300.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Back to Zero',
                'description' => '(Disinfect & Odor Elimination)',
                'price' => 350.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Back to Zero',
                'description' => '(Disinfect & Odor Elimination)',
                'price' => 350.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Body Wash',
                'description' => '(Outside Only)',
                'price' => 150.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Body Wash',
                'description' => '(Outside Only)',
                'price' => 180.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Body Wash',
                'description' => '(Outside Only)',
                'price' => 200.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Tire Black',
                'description' => '',
                'price' => 50.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tire Black',
                'description' => '',
                'price' => 50.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tire Black',
                'description' => '',
                'price' => 50.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Vacuum',
                'description' => '',
                'price' => 100.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vacuum',
                'description' => '',
                'price' => 150.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vacuum',
                'description' => '',
                'price' => 150.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Body Wax',
                'description' => '',
                'price' => 150.00,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Body Wax',
                'description' => '',
                'price' => 200.00,
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Body Wax',
                'description' => '',
                'price' => 250.00,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
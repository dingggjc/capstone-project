<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CategoryModel;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        CategoryModel::insert([
            [
                'category_name' => 'SMALL',
                'category_description' => 'Sedan, Hatchback & Multicab-Van.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'MEDIUM',
                'category_description' => 'SUV, MPV & Pick-Up',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'LARGE',
                'category_description' => 'High-End',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'OTHER SERVICES',
                'category_description' => 'Motorcycle, Big Bikes, RE/TVS/Piaggio, Multicab',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
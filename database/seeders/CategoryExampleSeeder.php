<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryExample;
use App\Models\CategoryModel;

class CategoryExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $categories = CategoryModel::all();

        foreach ($categories as $category) {

            $examples = match ($category->category_name) {
                'SMALL' => [
                    'Vios',
                    'City',
                    'Accent',
                    'Wigo',
                    'Picanto',
                    'Jimmy',
                    'Mirage',
                    'Swift',
                    'Ecosport',
                ],
                'MEDIUM' => [
                    'Innova',
                    'Avanza',
                    'Rush',
                    'Fortuner',
                    'Montero',
                    'Navara',
                    'Hilux',
                    'L300',
                    'Strada',
                    'Bongo',
                ],
                'LARGE' => [
                    'Pajero',
                    'Hi-Ace',
                    'Grandia',
                    'Starex',
                    'Land Cruiser',
                    'Raptor',
                    'Hummer',
                ],
                'OTHER SERVICES' => [
                    'Motorcycle',
                    'Big Bikes',
                    'RE/TVS/Piaggio',
                    'Multicab',
                ],
                default => [],
            };


            foreach ($examples as $exampleName) {
                CategoryExample::create([
                    'category_id' => $category->category_id,
                    'example_name' => $exampleName,
                ]);
            }
        }
    }
}
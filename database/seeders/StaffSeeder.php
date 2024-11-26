<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffModel;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaffModel::insert([
            [
                'staff_name' => 'Will Smith',
                'staff_phone' => '1234567890',
                'staff_status' => 'active',
            ],
            [
                'staff_name' => 'Jaden Smith',
                'staff_phone' => '0987654321',
                'staff_status' => 'active',
            ]
        ]);
    }
}
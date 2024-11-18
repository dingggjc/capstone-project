<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        if (Role::where('name', 'Gardenside Owner')->doesntExist()) {
            Role::create(['name' => 'admin']);
        }

        if (Role::where('name', 'cashier')->doesntExist()) {
            Role::create(['name' => 'cashier']);
        }


        if (!User::where('email', 'admin@example.com')->exists()) {

            $adminUser = User::factory()->create([
                'name' => 'John Doe',
                'email' => 'admin@example.com',
            ]);
            $adminUser->assignRole('admin');
        }


        if (!User::where('email', 'cashier@example.com')->exists()) {
            $cashierUser = User::factory()->create([
                'name' => 'Cashier User',
                'email' => 'cashier@example.com',
            ]);
            $cashierUser->assignRole('cashier');
        }
    }
}
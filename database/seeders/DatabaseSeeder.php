<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Check if the roles already exist before creating them
        if (Role::where('name', 'admin')->doesntExist()) {
            Role::create(['name' => 'admin']);
        }

        if (Role::where('name', 'cashier')->doesntExist()) {
            Role::create(['name' => 'cashier']);
        }

        // Check if the user with the email 'admin@example.com' already exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            // Create a test admin user and assign the admin role
            $adminUser = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ]);
            $adminUser->assignRole('admin'); // assign 'admin' role
        }

        // Check if the user with the email 'cashier@example.com' already exists
        if (!User::where('email', 'cashier@example.com')->exists()) {
            // Create a test cashier user and assign the cashier role
            $cashierUser = User::factory()->create([
                'name' => 'Cashier User',
                'email' => 'cashier@example.com',
            ]);
            $cashierUser->assignRole('cashier'); // assign 'cashier' role
        }
    }
}

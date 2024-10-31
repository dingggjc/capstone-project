<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create permissions
        $editPosts = Permission::firstOrCreate(['name' => 'edit posts']);
        $deletePosts = Permission::firstOrCreate(['name' => 'delete posts']);

        // Assign permissions to the admin role
        $adminRole->givePermissionTo([$editPosts, $deletePosts]);

        // Assign a role to a user
        $user = User::find(1); // Replace with the correct user ID
        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
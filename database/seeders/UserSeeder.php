<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superadminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create users
        $superadminUser = User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'), // Change this to a secure password
            'store_id' => 1,
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Change this to a secure password
            'store_id' => 1,
        ]);

        $cashierUser = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => bcrypt('password'),
            'store_id' => 1,
        ]);

        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'store_id' => 1,
        ]);

        // Assign roles to users
        $superadminUser->assignRole($superadminRole);
        $adminUser->assignRole($adminRole);
        $cashierUser->assignRole($cashierRole);
        $regularUser->assignRole($userRole);
    }
}

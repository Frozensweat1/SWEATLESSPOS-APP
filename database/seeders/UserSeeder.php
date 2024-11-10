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
        $adminUser = User::create([
            'name' => 'SuperAdmin User',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'), // Change this to a secure password
        ]);

        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Change this to a secure password
        ]);

        $cashierUser = User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => bcrypt('password'),
        ]);

        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign roles to users
        $adminUser->assignRole($adminRole);
        $cashierUser->assignRole($cashierRole);
        $regularUser->assignRole($userRole);
    }
}

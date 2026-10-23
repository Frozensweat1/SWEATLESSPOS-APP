<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            StoreSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            WebsitePagesSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            SaleSeeder::class,
        ]);
        // User::factory(10)->create();

    }
}

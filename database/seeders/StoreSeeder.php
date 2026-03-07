<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'id' => 1,
            'name' => config('app.name', 'Sweatless POS'),
            'address' => env('STORE_ADDRESS', 'Main Branch'),
            'phone' => env('STORE_PHONE', '+1 (555) 000-0000'),
            'email' => env('STORE_EMAIL', env('MAIL_FROM_ADDRESS')),
            'logo_path' => null,
            'initial_balance' => 0,
            'is_active' => true,
        ]);
    }
}

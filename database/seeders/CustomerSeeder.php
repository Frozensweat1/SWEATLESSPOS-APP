<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['Kwame Mensah', 'kwame.mensah@example.com', '0241000001', 'Accra, Osu'],
            ['Ama Serwaa', 'ama.serwaa@example.com', '0241000002', 'Kumasi, Adum'],
            ['Kofi Boateng', 'kofi.boateng@example.com', '0241000003', 'Tema, Community 4'],
            ['Akosua Owusu', 'akosua.owusu@example.com', '0241000004', 'Takoradi, Market Circle'],
            ['Yaw Asante', 'yaw.asante@example.com', '0241000005', 'Cape Coast, Abura'],
            ['Efua Darko', 'efua.darko@example.com', '0241000006', 'Accra, Madina'],
            ['Nana Adjei', 'nana.adjei@example.com', '0241000007', 'Tamale, Central'],
            ['Abena Frimpong', 'abena.frimpong@example.com', '0241000008', 'Accra, Spintex'],
            ['Kojo Appiah', 'kojo.appiah@example.com', '0241000009', 'Kumasi, Ahodwo'],
            ['Adwoa Quaye', 'adwoa.quaye@example.com', '0241000010', 'Accra, Labone'],
        ];

        foreach ($customers as $i => [$name, $email, $phone, $address]) {
            Customer::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $phone,
                    'address' => $address,
                    'loyalty_points' => ($i * 17) % 120,
                    'status' => $i === 9 ? 'inactive' : 'active',
                    'store_id' => 1,
                ]
            );
        }
    }
}

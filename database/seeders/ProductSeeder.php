<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Beverages' => [
                ['Bottled Water 500ml', 3.00, 1.80, 200],
                ['Orange Juice 1L', 14.00, 9.50, 60],
                ['Cola 350ml', 5.00, 3.20, 120],
                ['Energy Drink', 12.00, 8.00, 3],
            ],
            'Snacks' => [
                ['Potato Chips', 6.00, 3.80, 90],
                ['Chocolate Bar', 8.00, 5.00, 75],
                ['Peanut Butter Cookies', 10.00, 6.50, 40],
                ['Roasted Groundnuts', 4.00, 2.30, 0],
            ],
            'Household' => [
                ['Laundry Detergent 1kg', 28.00, 20.00, 35],
                ['Dish Soap 500ml', 12.00, 7.50, 50],
                ['Toilet Tissue 4-pack', 18.00, 12.00, 45],
                ['Bleach 1L', 11.00, 7.00, 2],
            ],
            'Personal Care' => [
                ['Bath Soap', 5.50, 3.20, 100],
                ['Toothpaste 100ml', 9.00, 5.80, 80],
                ['Shea Body Lotion 250ml', 22.00, 14.00, 30],
                ['Deodorant Roll-on', 15.00, 9.50, 55],
            ],
        ];

        $n = 1;
        foreach ($catalog as $categoryName => $products) {
            $category = Category::firstOrCreate(
                ['name' => $categoryName, 'store_id' => 1],
                ['description' => $categoryName . ' products']
            );

            foreach ($products as [$name, $price, $cost, $stock]) {
                Product::updateOrCreate(
                    ['sku' => sprintf('SKU-%04d', $n)],
                    [
                        'name' => $name,
                        'barcode' => sprintf('600%010d', $n),
                        'description' => $name,
                        'price' => $price,
                        'cost_price' => $cost,
                        'stock_quantity' => $stock,
                        'category_id' => $category->id,
                        'store_id' => 1,
                        'is_active' => true,
                    ]
                );
                $n++;
            }
        }
    }
}

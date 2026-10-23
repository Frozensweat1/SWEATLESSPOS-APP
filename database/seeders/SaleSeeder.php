<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    public function run(int $count = 40): void
    {
        $users = User::where('store_id', 1)->pluck('id');
        $customerIds = Customer::where('store_id', 1)->pluck('id');

        if ($users->isEmpty() || Product::where('store_id', 1)->doesntExist()) {
            $this->command?->warn('SaleSeeder needs users and products; skipping.');
            return;
        }

        mt_srand(42);

        for ($i = 0; $i < $count; $i++) {
            DB::transaction(function () use ($users, $customerIds) {
                $products = Product::where('store_id', 1)
                    ->where('stock_quantity', '>', 0)
                    ->inRandomOrder()
                    ->limit(mt_rand(1, 4))
                    ->lockForUpdate()
                    ->get();

                if ($products->isEmpty()) {
                    return;
                }

                $lines = [];
                $total = 0;
                foreach ($products as $product) {
                    $qty = min(mt_rand(1, 3), $product->stock_quantity);
                    $subtotal = round($product->price * $qty, 2);
                    $total += $subtotal;
                    $lines[] = [$product, $qty, $subtotal];
                }

                $status = collect(['paid', 'paid', 'paid', 'partial'])->random();
                $paid = $status === 'paid' ? $total : round($total * mt_rand(30, 70) / 100, 2);
                $createdAt = now()->subDays(mt_rand(0, 30))->setTime(mt_rand(8, 19), mt_rand(0, 59));
                $userId = $users->random();

                $sale = Sale::create([
                    'store_id' => 1,
                    'customer_id' => $customerIds->isNotEmpty() && mt_rand(0, 2) ? $customerIds->random() : null,
                    'user_id' => $userId,
                    'total_amount' => $total,
                    'discount_amount' => 0,
                    'total_discount' => 0,
                    'payment_method' => collect(['cash', 'card', 'mobile_money'])->random(),
                    'payment_status' => $status,
                    'amount_paid' => $paid,
                    'amount_due' => round($total - $paid, 2),
                ]);
                $sale->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->saveQuietly();

                foreach ($lines as [$product, $qty, $subtotal]) {
                    SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $qty,
                        'price_per_unit' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    $before = $product->stock_quantity;
                    $product->update(['stock_quantity' => $before - $qty]);

                    StockAdjustment::create([
                        'store_id' => 1,
                        'product_id' => $product->id,
                        'user_id' => $userId,
                        'type' => 'sale',
                        'quantity_before' => $before,
                        'quantity_after' => $before - $qty,
                        'change_qty' => -$qty,
                        'reason' => 'Sale #' . $sale->id,
                    ]);
                }
            });
        }
    }
}

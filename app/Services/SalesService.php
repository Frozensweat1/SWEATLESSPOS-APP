<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesService
{
    /**
     * Add item to cart
     *
     * @param array $cart
     * @param int $productId
     * @param float $price
     * @param int $quantity
     * @return array
     */
    public function addToCart(array $cart, int $productId, float $price, int $quantity = 1): array
    {
        // If item already in cart, increase quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        return $cart;
    }

    /**
     * Remove item from cart
     *
     * @param array $cart
     * @param int $productId
     * @return array
     */
    public function removeFromCart(array $cart, int $productId): array
    {
        unset($cart[$productId]);
        return $cart;
    }

    /**
     * Update item quantity in cart
     *
     * @param array $cart
     * @param int $productId
     * @param int $quantity
     * @return array
     */
    public function updateCartItemQuantity(array $cart, int $productId, int $quantity): array
    {
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
        }

        return $cart;
    }

    /**
     * Calculate cart totals
     *
     * @param array $cart
     * @return array
     */
    public function calculateCartTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return [
            'subtotal' => $subtotal,
            'item_count' => count($cart),
            'total_quantity' => array_reduce($cart, fn($sum, $item) => $sum + $item['quantity'], 0),
        ];
    }

    /**
     * Apply discount to sale
     *
     * @param float $subtotal
     * @param string $discountType
     * @param float $discountValue
     * @return array
     */
    public function calculateDiscount(float $subtotal, string $discountType, float $discountValue): array
    {
        $discountAmount = 0;

        if ($discountType === 'percentage') {
            $discountAmount = ($subtotal * $discountValue) / 100;
        } elseif ($discountType === 'fixed') {
            $discountAmount = $discountValue;
        }

        $total = $subtotal - $discountAmount;

        return [
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'discount_amount' => $discountAmount,
            'total' => max(0, $total), // Ensure total is not negative
        ];
    }

    /**
     * Create a new sale
     *
     * @param int $storeId
     * @param array $cartItems
     * @param int|null $customerId
     * @param string $paymentMethod
     * @param float $amountPaid
     * @param array $discountInfo
     * @return Sale
     * @throws \Exception
     */
    public function createSale(
        int $storeId,
        array $cartItems,
        ?int $customerId,
        string $paymentMethod,
        float $amountPaid,
        array $discountInfo = []
    ): Sale {
        if (empty($cartItems)) {
            throw new \Exception('Cart is empty.');
        }

        return DB::transaction(function () use (
            $storeId,
            $cartItems,
            $customerId,
            $paymentMethod,
            $amountPaid,
            $discountInfo
        ) {
            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discountAmount = $discountInfo['discount_amount'] ?? 0;
            $totalAmount = $subtotal - $discountAmount;

            // Create sale record
            $sale = Sale::create([
                'store_id' => $storeId,
                'customer_id' => $customerId,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'discount_type' => $discountInfo['discount_type'] ?? null,
                'discount_value' => $discountInfo['discount_value'] ?? 0,
                'discount_amount' => $discountAmount,
                'total_discount' => $discountAmount,
                'payment_method' => $paymentMethod,
                'payment_status' => $amountPaid >= $totalAmount ? 'paid' : 'partial',
                'amount_paid' => $amountPaid,
                'amount_due' => max(0, $totalAmount - $amountPaid),
            ]);

            // Create sale details and update stock
            foreach ($cartItems as $productId => $item) {
                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::findOrFail($item['product_id']);
                $quantityBefore = $product->stock_quantity;
                $quantityAfter = $quantityBefore - $item['quantity'];
                $product->update(['stock_quantity' => $quantityAfter]);

                // Record stock adjustment
                StockAdjustment::create([
                    'store_id' => $storeId,
                    'product_id' => $item['product_id'],
                    'user_id' => Auth::id(),
                    'type' => 'sale',
                    'quantity_before' => $quantityBefore,
                    'quantity_after' => $quantityAfter,
                    'change_qty' => -$item['quantity'],
                    'reason' => 'Sale #' . $sale->id,
                ]);
            }

            return $sale;
        });
    }

    /**
     * Get recent sales for a store
     *
     * @param int $storeId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentSales(int $storeId, int $limit = 15)
    {
        return Sale::where('store_id', $storeId)
            ->with(['customer', 'saleDetails.product'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

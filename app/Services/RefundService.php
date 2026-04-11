<?php

namespace App\Services;

use App\Models\Refund;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundService
{
    /**
     * Process a refund for a sale detail
     *
     * @param int $storeId
     * @param int $saleDetailId
     * @param int $quantity
     * @param string $reason
     * @return Refund
     * @throws \Exception
     */
    public function processSaleDetailRefund(
        int $storeId,
        int $saleDetailId,
        int $quantity,
        string $reason = ''
    ): Refund {
        return DB::transaction(function () use ($storeId, $saleDetailId, $quantity, $reason) {
            $saleDetail = SaleDetail::findOrFail($saleDetailId);

            // Validate quantity
            if ($quantity <= 0 || $quantity > $saleDetail->quantity) {
                throw new \Exception('Invalid refund quantity.');
            }

            // Get product and verify stock
            $product = Product::findOrFail($saleDetail->product_id);

            // Calculate refund amount
            $refundAmount = $saleDetail->price_per_unit * $quantity;

            // Create refund record
            $refund = Refund::create([
                'sale_id' => $saleDetail->sale_id,
                'sale_detail_id' => $saleDetailId,
                'product_id' => $saleDetail->product_id,
                'quantity' => $quantity,
                'refund_amount' => $refundAmount,
                'reason' => $reason,
                'user_id' => Auth::id(),
                'store_id' => $storeId,
            ]);

            // Restore product stock
            $quantityBefore = $product->stock_quantity;
            $product->increment('stock_quantity', $quantity);
            $quantityAfter = $product->fresh()->stock_quantity;

            // Record stock adjustment
            StockAdjustment::create([
                'store_id' => $storeId,
                'product_id' => $saleDetail->product_id,
                'user_id' => Auth::id(),
                'type' => 'refund',
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'change_qty' => $quantity,
                'reason' => 'Refund #' . $refund->id . ' - ' . $reason,
            ]);

            // Update sale amount if full refund of all items from that detail
            $this->updateSaleBalanceAfterRefund($saleDetail->sale_id);

            return $refund;
        });
    }

    /**
     * Process multiple refunds (bulk refund)
     *
     * @param int $storeId
     * @param array $refunds
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function processBulkRefunds(int $storeId, array $refunds): \Illuminate\Support\Collection
    {
        return DB::transaction(function () use ($storeId, $refunds) {
            $processedRefunds = collect();

            foreach ($refunds as $refundData) {
                $refund = $this->processSaleDetailRefund(
                    $storeId,
                    $refundData['sale_detail_id'],
                    $refundData['quantity'],
                    $refundData['reason'] ?? ''
                );
                $processedRefunds->push($refund);
            }

            return $processedRefunds;
        });
    }

    /**
     * Get refunds with search and filters
     *
     * @param int $storeId
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getRefunds(int $storeId, array $filters = [], int $perPage = 10)
    {
        $query = Refund::where('store_id', $storeId)
            ->with(['product', 'user']);

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Cancel a refund (reverse the refund)
     *
     * @param int $storeId
     * @param int $refundId
     * @return bool
     * @throws \Exception
     */
    public function cancelRefund(int $storeId, int $refundId): bool
    {
        return DB::transaction(function () use ($storeId, $refundId) {
            $refund = Refund::where('store_id', $storeId)->findOrFail($refundId);

            // Restore stock (remove the previously restored stock)
            $quantityBefore = $refund->product->stock_quantity;
            Product::findOrFail($refund->product_id)->decrement('stock_quantity', $refund->quantity);
            $quantityAfter = Product::findOrFail($refund->product_id)->fresh()->stock_quantity;

            // Delete stock adjustment related to this refund by matching the reason pattern
            StockAdjustment::where('reason', 'like', 'Refund #' . $refundId . '%')->delete();

            // Record reversal stock adjustment
            StockAdjustment::create([
                'store_id' => $storeId,
                'product_id' => $refund->product_id,
                'user_id' => Auth::id(),
                'type' => 'refund_reversal',
                'quantity_before' => $quantityBefore,
                'quantity_after' => $quantityAfter,
                'change_qty' => -$refund->quantity,
                'reason' => 'Refund #' . $refundId . ' cancelled',
            ]);

            // Delete refund record
            $refund->delete();

            // Restore sale balance
            $this->updateSaleBalanceAfterRefund($refund->sale_id);

            return true;
        });
    }

    /**
     * Update sale balance after refund
     *
     * @param int $saleId
     * @return void
     */
    private function updateSaleBalanceAfterRefund(int $saleId): void
    {
        $sale = Sale::findOrFail($saleId);

        // Calculate total refunded amount for this sale
        $totalRefunded = Refund::where('sale_id', $saleId)->sum('refund_amount');

        // Update sale amount due
        $amountDue = $sale->total_amount - $sale->amount_paid - $totalRefunded;
        $sale->update([
            'amount_due' => max(0, $amountDue),
        ]);
    }

    /**
     * Get refund summary for reporting
     *
     * @param int $storeId
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     * @return array
     */
    public function getRefundSummary(int $storeId, ?\DateTime $startDate = null, ?\DateTime $endDate = null): array
    {
        $query = Refund::where('store_id', $storeId);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return [
            'total_refunds' => $query->count(),
            'total_refund_amount' => $query->sum('refund_amount'),
            'refunds_by_product' => $query->groupBy('product_id')
                ->selectRaw('product_id, COUNT(*) as count, SUM(refund_amount) as amount')
                ->get(),
        ];
    }
}

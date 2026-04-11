<?php

namespace App\Services;

use App\Models\CashDrawerSession;
use App\Models\Expenditure;
use App\Models\Refund;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class CashDrawerService
{
    /**
     * Open a new cash drawer session
     *
     * @param int $storeId
     * @param float $openingBalance
     * @param string|null $openingNote
     * @return CashDrawerSession
     * @throws \Exception
     */
    public function openDrawer(int $storeId, float $openingBalance, ?string $openingNote = null): CashDrawerSession
    {
        // Check if user already has an open session
        if ($this->hasOpenSession($storeId)) {
            throw new \Exception('You already have an open cash drawer session.');
        }

        return CashDrawerSession::create([
            'store_id' => $storeId,
            'user_id' => Auth::id(),
            'opening_balance' => $openingBalance,
            'opening_note' => $openingNote,
            'status' => 'open',
            'opened_at' => now(),
        ]);
    }

    /**
     * Close the current cash drawer session
     *
     * @param int $storeId
     * @param float $closingBalance
     * @param string|null $closingNote
     * @return CashDrawerSession
     * @throws \Exception
     */
    public function closeDrawer(int $storeId, float $closingBalance, ?string $closingNote = null): CashDrawerSession
    {
        $session = $this->getOpenSession($storeId);

        if (!$session) {
            throw new \Exception('No open cash drawer session found.');
        }

        $summary = $this->calculateCashSummary($storeId, $session->opened_at, now());
        $expected = $session->opening_balance + $summary['cash_sales'] - $summary['cash_refunds'] - $summary['cash_expenses'];
        $discrepancy = $closingBalance - $expected;

        $session->update([
            'closing_balance' => $closingBalance,
            'closing_note' => $closingNote,
            'cash_sales' => $summary['cash_sales'],
            'cash_refunds' => $summary['cash_refunds'],
            'cash_expenses' => $summary['cash_expenses'],
            'expected_cash' => $expected,
            'discrepancy' => $discrepancy,
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return $session;
    }

    /**
     * Get the current open session for a store
     *
     * @param int $storeId
     * @return CashDrawerSession|null
     */
    public function getOpenSession(int $storeId): ?CashDrawerSession
    {
        return CashDrawerSession::where('store_id', $storeId)
            ->where('user_id', Auth::id())
            ->where('status', 'open')
            ->orderByDesc('opened_at')
            ->first();
    }

    /**
     * Check if the current user has an open session
     *
     * @param int $storeId
     * @return bool
     */
    public function hasOpenSession(int $storeId): bool
    {
        return CashDrawerSession::where('store_id', $storeId)
            ->where('user_id', Auth::id())
            ->where('status', 'open')
            ->exists();
    }

    /**
     * Calculate cash summary (sales, refunds, expenses) for a time period
     *
     * @param int $storeId
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function calculateCashSummary(int $storeId, \DateTime $start, \DateTime $end): array
    {
        $cashSales = Sale::where('store_id', $storeId)
            ->where('payment_method', 'cash')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_amount');

        $cashRefunds = Refund::where('store_id', $storeId)
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('sale', function ($query) {
                $query->where('payment_method', 'cash');
            })
            ->sum('refund_amount');

        $startDate = \Carbon\Carbon::parse($start)->toDateString();
        $endDate = \Carbon\Carbon::parse($end)->toDateString();
        $cashExpenses = Expenditure::where('store_id', $storeId)
            ->where('payment_method', 'cash')
            ->whereBetween('expenditure_date', [$startDate, $endDate])
            ->sum('amount');

        return [
            'cash_sales' => (float) $cashSales,
            'cash_refunds' => (float) $cashRefunds,
            'cash_expenses' => (float) $cashExpenses,
        ];
    }

    /**
     * Get live summary for an open session
     *
     * @param CashDrawerSession $session
     * @return array
     */
    public function getLiveSummary(CashDrawerSession $session): array
    {
        $summary = $this->calculateCashSummary($session->store_id, $session->opened_at, now());
        $expected = $session->opening_balance + $summary['cash_sales'] - $summary['cash_refunds'] - $summary['cash_expenses'];

        return [
            'cash_sales' => $summary['cash_sales'],
            'cash_refunds' => $summary['cash_refunds'],
            'cash_expenses' => $summary['cash_expenses'],
            'expected_cash' => $expected,
        ];
    }
}

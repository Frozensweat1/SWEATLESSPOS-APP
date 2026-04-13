<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class DailySalesSummary extends Component
{
    use StoreScoped;

    public $startDate;
    public $endDate;
    public $groupBy = 'day';

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function getDailySalesProperty()
    {
        $dateFormat = match($this->groupBy) {
            'hour' => '%Y-%m-%d %H:00',
            'week' => '%Y-W%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        return Sale::where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(CASE WHEN payment_method = "cash" THEN total_amount ELSE 0 END) as cash_total'),
                DB::raw('SUM(CASE WHEN payment_method = "mobile_money" THEN total_amount ELSE 0 END) as mobile_total'),
                DB::raw('SUM(CASE WHEN payment_method = "credit_card" THEN total_amount ELSE 0 END) as card_total')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    public function getSummaryProperty()
    {
        $sales = Sale::where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $totalRevenue = $sales->sum('total_amount');
        $transactionCount = $sales->count();

        $dailyAvg = $this->dailySales->count() > 0 
            ? $this->dailySales->sum('total_revenue') / $this->dailySales->count() 
            : 0;

        $bestDay = $this->dailySales->sortByDesc('total_revenue')->first();
        $worstDay = $this->dailySales->sortBy('total_revenue')->first();

        return [
            'totalRevenue' => $totalRevenue,
            'transactionCount' => $transactionCount,
            'averageDaily' => $dailyAvg,
            'bestDay' => $bestDay,
            'worstDay' => $worstDay,
        ];
    }

    public function render()
    {
        return view('livewire.daily-sales-summary', [
            'dailySales' => $this->dailySales,
            'summary' => $this->summary,
        ]);
    }
}
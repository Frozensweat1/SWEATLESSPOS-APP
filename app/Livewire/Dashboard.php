<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Expenditure;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use StoreScoped;

    public $totalSales;
    public $totalSaleDetails;
    public $totalExpenditures;
    public $totalProducts;
    public $totalNotifications;
    public $totalUsers;
    public $totalRevenue;
    public $totalExpenses;
    public $totalCostOfGoods;
    public $grossProfit;
    public $netProfit;
    public $fromDate;
    public $toDate;

    public function mount()
    {
        $this->initStoreId();
        $today = Carbon::today()->toDateString();
        $this->fromDate = $today;
        $this->toDate = $today;
        $this->loadTotals();
    }

    protected function getDateRange()
    {
        $from = $this->fromDate ?: Carbon::today()->toDateString();
        $to = $this->toDate ?: $from;

        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        return [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay(),
        ];
    }

    public function applyDateFilter()
    {
        $this->loadTotals();
    }

    public function loadTotals()
    {
        [$start, $end] = $this->getDateRange();
        $dateOnlyRange = [$start->toDateString(), $end->toDateString()];

        // Count totals for each model - store scoped
        $this->totalSales = Sale::where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $this->totalSaleDetails = SaleDetail::where('is_refunded', false)
            ->whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId()))
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $this->totalExpenditures = Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', $dateOnlyRange)
            ->count();
        $this->totalProducts = Product::where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $this->totalNotifications = Notification::whereBetween('created_at', [$start, $end])->count();
        $this->totalUsers = User::whereBetween('created_at', [$start, $end])->count();

        // Calculate total revenue (excluding refunded sale details)
        $this->totalRevenue = SaleDetail::where('is_refunded', false)
            ->whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId()))
            ->whereBetween('created_at', [$start, $end])
            ->sum('subtotal');

        // Calculate total expenses (sum of amount in expenditures)
        $this->totalExpenses = Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', $dateOnlyRange)
            ->sum('amount');

        // Total cost of goods sold (sum of quantity * cost_price)
        $this->totalCostOfGoods = SaleDetail::where('sale_details.is_refunded', false)
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->where('sales.store_id', $this->getStoreId())
            ->whereBetween('sales.created_at', [$start, $end])
            ->sum(DB::raw('sale_details.quantity * products.cost_price'));

        $this->grossProfit = $this->totalRevenue - $this->totalCostOfGoods;
        $this->netProfit = $this->grossProfit - $this->totalExpenses;
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

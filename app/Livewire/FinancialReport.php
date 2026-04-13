<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Expenditure;
use App\Models\Product;
use App\Models\User;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class FinancialReport extends Component
{
    use WithPagination, StoreScoped;

    public $startDate;
    public $endDate;
    public $userId;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getSalesBaseQuery()
    {
        return Sale::where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId));
    }

    public function getExpendituresBaseQuery()
    {
        return Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', [$this->startDate, $this->endDate])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId));
    }

    public function getTotalSalesProperty()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId)))
            ->where('is_refunded', false)
            ->sum('subtotal');
    }

    public function getTotalExpendituresProperty()
    {
        return $this->getExpendituresBaseQuery()->sum('amount');
    }

    public function getTotalRefundedProperty()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId)))
            ->where('is_refunded', true)
            ->sum('subtotal');
    }

    public function getTotalCostProperty()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->userId, fn($q) => $q->where('user_id', $this->userId)))
            ->where('is_refunded', false)
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->sum(DB::raw('sale_details.quantity * products.cost_price'));
    }

    public function getNetProfitProperty()
    {
        return $this->totalSales - $this->totalExpenditures - $this->totalCost;
    }

    public function getGrossMarginProperty()
    {
        if ($this->totalSales > 0) {
            return (($this->totalSales - $this->totalCost) / $this->totalSales) * 100;
        }
        return 0;
    }

    public function getAverageTransactionProperty()
    {
        $count = $this->getSalesBaseQuery()->count();
        return $count > 0 ? $this->totalSales / $count : 0;
    }

    public function getSalesByPaymentMethodProperty()
    {
        return $this->getSalesBaseQuery()
            ->select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
    }

    public function getTotalInventoryValueProperty()
    {
        return Product::where('store_id', $this->getStoreId())
            ->selectRaw('COALESCE(SUM(stock_quantity * price), 0) as total_inventory_value')
            ->value('total_inventory_value');
    }

    public function getTotalInventoryCostProperty()
    {
        return Product::where('store_id', $this->getStoreId())
            ->selectRaw('COALESCE(SUM(stock_quantity * cost_price), 0) as total_inventory_cost')
            ->value('total_inventory_cost');
    }

    public function render()
    {
        $sales = $this->getSalesBaseQuery()->with(['user', 'customer'])->paginate($this->perPage);
        $expenditures = $this->getExpendituresBaseQuery()->with(['user'])->paginate($this->perPage);
        $users = User::role(['super-admin', 'admin', 'cashier'])->get();

        return view('livewire.financial-report', [
            'sales' => $sales,
            'expenditures' => $expenditures,
            'totalSales' => $this->totalSales,
            'totalExpenditures' => $this->totalExpenditures,
            'totalRefunded' => $this->totalRefunded,
            'totalCost' => $this->totalCost,
            'netProfit' => $this->netProfit,
            'grossMargin' => $this->grossMargin,
            'averageTransaction' => $this->averageTransaction,
            'salesByPaymentMethod' => $this->salesByPaymentMethod,
            'totalInventoryValue' => $this->totalInventoryValue,
            'totalInventoryCost' => $this->totalInventoryCost,
            'users' => $users,
        ]);
    }
}

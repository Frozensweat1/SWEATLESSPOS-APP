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

    public function render()
    {
        // Query sales with non-refunded SaleDetails only
        $salesQuery = Sale::where('store_id', $this->getStoreId())
            ->with(['saleDetails' => function ($query) {
                $query->where('is_refunded', false);
            }]);

        $expendituresQuery = Expenditure::where('store_id', $this->getStoreId());

        if ($this->startDate && $this->endDate) {
            $salesQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
            $expendituresQuery->whereBetween('expenditure_date', [$this->startDate, $this->endDate]);
        }

        if ($this->userId) {
            $salesQuery->where('user_id', $this->userId);
            $expendituresQuery->where('user_id', $this->userId);
        }

        // Calculate total sales by summing non-refunded SaleDetail subtotals
        $totalSales = $salesQuery->get()->reduce(function ($total, $sale) {
            return $total + $sale->saleDetails->sum('subtotal');
        }, 0);

        // Calculate total expenditures
        $totalExpenditures = $expendituresQuery->sum('amount');

        // Paginate sales and expenditures
        $sales = $salesQuery->paginate($this->perPage);
        $expenditures = $expendituresQuery->paginate($this->perPage);

        // Calculate total inventory value: Sum of (stock_quantity * price) for all products in this store.
        $totalInventoryValue = Product::where('store_id', $this->getStoreId())
            ->select(DB::raw('SUM(stock_quantity * price) as total_inventory_value'))
            ->value('total_inventory_value');

        // Fetch users with specific roles using Spatie
        $users = User::role(['super-admin', 'admin', 'cashier'])->get();

        return view('livewire.financial-report', [
            'sales' => $sales,
            'expenditures' => $expenditures,
            'totalSales' => $totalSales,
            'totalExpenditures' => $totalExpenditures,
            'totalInventoryValue' => $totalInventoryValue,
            'users' => $users,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Expenditure;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class FinancialReport extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $userId;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        // Query sales with non-refunded SaleDetails only
        $salesQuery = Sale::with(['saleDetails' => function ($query) {
            $query->where('is_refunded', false);
        }]);

        $expendituresQuery = Expenditure::query();

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

        // Fetch users with specific roles using Spatie
        $users = User::role(['super-admin', 'admin', 'cashier'])->get();

        return view('livewire.financial-report', [
            'sales' => $sales,
            'expenditures' => $expenditures,
            'totalSales' => $totalSales,
            'totalExpenditures' => $totalExpenditures,
            'users' => $users,
        ]);
    }
}

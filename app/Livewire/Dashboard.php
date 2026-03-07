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

    public function mount()
    {
        $this->initStoreId();
        $this->loadTotals();
    }

    public function loadTotals()
    {
        // Count totals for each model - store scoped
        $this->totalSales = Sale::where('store_id', $this->getStoreId())->count();
        $this->totalSaleDetails = SaleDetail::where('is_refunded', false)
            ->whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId()))
            ->count();
        $this->totalExpenditures = Expenditure::where('store_id', $this->getStoreId())->count();
        $this->totalProducts = Product::where('store_id', $this->getStoreId())->count();
        $this->totalNotifications = Notification::count();
        $this->totalUsers = User::count();

        // Calculate total revenue (excluding refunded sale details)
        $this->totalRevenue = SaleDetail::where('is_refunded', false)
            ->whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId()))
            ->sum('subtotal');

        // Calculate total expenses (sum of amount in expenditures)
        $this->totalExpenses = Expenditure::where('store_id', $this->getStoreId())->sum('amount');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

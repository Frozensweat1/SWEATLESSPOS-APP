<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Expenditure;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;

class Dashboard extends Component
{
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
        $this->loadTotals();
    }

    public function loadTotals()
    {
        // Count totals for each model
        $this->totalSales = Sale::count();
        $this->totalSaleDetails = SaleDetail::where('is_refunded', false)->count();
        $this->totalExpenditures = Expenditure::count();
        $this->totalProducts = Product::count();
        $this->totalNotifications = Notification::count();
        $this->totalUsers = User::count();

        // Calculate total revenue (excluding refunded sale details)
        $this->totalRevenue = SaleDetail::where('is_refunded', false)->sum('subtotal');

        // Calculate total expenses (sum of amount in expenditures)
        $this->totalExpenses = Expenditure::sum('amount');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

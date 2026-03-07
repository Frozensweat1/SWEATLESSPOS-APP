<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class SalesDashboard extends Component
{
    public $salesTrends = []; // Sales data for the line chart
    public $categorySales = []; // Category sales for the bar chart
    public $paymentMethods = []; // Payment methods for the pie chart

    public function mount()
    {
        $this->fetchSalesData();
    }

    public function fetchSalesData()
    {
        $this->salesTrends = SaleDetail::where('is_refunded', false)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw("SUM(subtotal) as total")
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray(); // Ensure conversion to array

         $this->categorySales = SaleDetail::where('is_refunded', false)
       ->join('products', 'sale_details.product_id', '=', 'products.id')
       ->join('categories', 'products.category_id', '=', 'categories.id')
       ->select('categories.name as category_name', DB::raw('SUM(sale_details.subtotal) as total'))
       ->groupBy('categories.id', 'categories.name') // Group by category ID and name
       ->pluck('total', 'category_name') // Pluck total with category name as key
       ->toArray(); //Ensure conversion to array

        $this->paymentMethods = SaleDetail::where('is_refunded', false)
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->select('sales.payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('sales.payment_method')
            ->pluck('count', 'sales.payment_method')
            ->toArray(); // Ensure conversion to array
    }
    public function render()
    {
        return view('livewire.sales-dashboard');
    }
}

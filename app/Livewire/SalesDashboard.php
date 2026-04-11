<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use App\Traits\StoreScoped;

#[Layout('layouts.app')]
class SalesDashboard extends Component
{
    use StoreScoped;

    public $salesTrends = []; // Sales data for the line chart
    public $categorySales = []; // Category sales for the bar chart
    public $paymentMethods = []; // Payment methods for the pie chart
    public $fromDate;
    public $toDate;
    public $componentName;
    public $pageTitle;

    public function mount()
    {
        $this->initStoreId();
        $this->componentName = 'Sales Dashboard';
        $this->pageTitle = 'Analytics';
        $today = Carbon::today()->toDateString();
        $this->fromDate = $today;
        $this->toDate = $today;
        $this->fetchSalesData();
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
        $this->fetchSalesData();
    }

    public function fetchSalesData()
    {
        [$start, $end] = $this->getDateRange();
        $storeId = $this->getStoreId();

        $salesTrendsQuery = SaleDetail::where('sale_details.is_refunded', false)
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.store_id', $storeId)
            ->whereBetween('sales.created_at', [$start, $end])
            ->select(
                DB::raw("DATE(sales.created_at) as day"),
                DB::raw("SUM(subtotal) as total")
            )
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray(); // Ensure conversion to array
        $this->salesTrends = $salesTrendsQuery;

        $categorySalesQuery = SaleDetail::where('sale_details.is_refunded', false)
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->join('products', 'sale_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.store_id', $storeId)
            ->whereBetween('sales.created_at', [$start, $end])
            ->select('categories.name as category_name', DB::raw('SUM(sale_details.subtotal) as total'))
            ->groupBy('categories.id', 'categories.name') // Group by category ID and name
            ->pluck('total', 'category_name') // Pluck total with category name as key
            ->toArray(); //Ensure conversion to array
        $this->categorySales = $categorySalesQuery;

        $this->paymentMethods = SaleDetail::where('sale_details.is_refunded', false)
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.store_id', $storeId)
            ->whereBetween('sales.created_at', [$start, $end])
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

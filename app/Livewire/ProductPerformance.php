<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Pagination\LengthAwarePaginator;

#[Layout('layouts.app')]
class ProductPerformance extends Component
{
    use StoreScoped;

    public $startDate;
    public $endDate;
    public $sortBy = 'revenue';
    public $perPage = 15;
    public $page = 1;

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
    }

    public function updatedPage()
    {
        $this->render();
    }

    public function getProductsProperty()
    {
        $productIds = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate))
            ->where('is_refunded', false)
            ->distinct()
            ->pluck('product_id');

        $products = Product::where('store_id', $this->getStoreId())
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) {
                $details = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('created_at', '>=', $this->startDate)
                    ->where('created_at', '<=', $this->endDate))
                    ->where('product_id', $product->id)
                    ->where('is_refunded', false)
                    ->get();

                $quantitySold = $details->sum('quantity');
                $revenue = $details->sum('subtotal');
                $cost = $details->sum(fn($d) => $d->quantity * ($product->cost_price ?? 0));
                $profit = $revenue - $cost;

                return (object) [
                    'product' => $product,
                    'quantitySold' => $quantitySold,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'profit' => $profit,
                    'stockValue' => $product->stock_quantity * $product->price,
                ];
            })
            ->filter(fn($p) => $p->quantitySold > 0 || $p->product->stock_quantity > 0);

        $sorted = match($this->sortBy) {
            'profit' => $products->sortByDesc('profit'),
            'quantity' => $products->sortByDesc('quantitySold'),
            default => $products->sortByDesc('revenue'),
        };

        $items = $sorted->forPage($this->page, $this->perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $sorted->count(),
            $this->perPage,
            $this->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getStatsProperty()
    {
        $productIds = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate))
            ->where('is_refunded', false)
            ->distinct()
            ->pluck('product_id');

        $products = Product::where('store_id', $this->getStoreId())
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) {
                $details = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('created_at', '>=', $this->startDate)
                    ->where('created_at', '<=', $this->endDate))
                    ->where('product_id', $product->id)
                    ->where('is_refunded', false)
                    ->get();

                $quantitySold = $details->sum('quantity');
                $revenue = $details->sum('subtotal');
                $cost = $details->sum(fn($d) => $d->quantity * ($product->cost_price ?? 0));
                $profit = $revenue - $cost;

                return (object) [
                    'revenue' => $revenue,
                    'quantitySold' => $quantitySold,
                    'cost' => $cost,
                    'profit' => $profit,
                ];
            });

        $totalRevenue = $products->sum('revenue');
        $totalQuantity = $products->sum('quantitySold');
        $totalCost = $products->sum('cost');
        $totalProfit = $totalRevenue - $totalCost;

        $topProduct = $products->sortByDesc('revenue')->first();

        return [
            'totalRevenue' => $totalRevenue,
            'totalQuantity' => $totalQuantity,
            'totalProfit' => $totalProfit,
            'topProduct' => $topProduct,
        ];
    }

    public function getTopSellersProperty()
    {
        $productIds = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate))
            ->where('is_refunded', false)
            ->distinct()
            ->pluck('product_id');

        $products = Product::where('store_id', $this->getStoreId())
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) {
                $quantitySold = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('created_at', '>=', $this->startDate)
                    ->where('created_at', '<=', $this->endDate))
                    ->where('product_id', $product->id)
                    ->where('is_refunded', false)
                    ->sum('quantity');

                return (object) [
                    'product' => $product,
                    'quantitySold' => $quantitySold,
                ];
            })
            ->sortByDesc('quantitySold')
            ->take(5);

        return $products;
    }

    public function getSlowMoversProperty()
    {
        return Product::where('store_id', $this->getStoreId())
            ->where('stock_quantity', '>', 0)
            ->get()
            ->map(fn($p) => (object) [
                'product' => $p, 
                'quantitySold' => SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('created_at', '>=', $this->startDate)
                    ->where('created_at', '<=', $this->endDate))
                    ->where('product_id', $p->id)->where('is_refunded', false)->sum('quantity')
            ])
            ->sortBy('quantitySold')
            ->take(5);
    }

    public function export()
    {
        $products = $this->products;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Product');
        $sheet->setCellValue('B1', 'SKU');
        $sheet->setCellValue('C1', 'Qty Sold');
        $sheet->setCellValue('D1', 'Revenue');
        $sheet->setCellValue('E1', 'Cost');
        $sheet->setCellValue('F1', 'Profit');
        $sheet->setCellValue('G1', 'Stock');

        $row = 2;
        foreach ($products as $p) {
            $sheet->setCellValue("A{$row}", $p->product->name);
            $sheet->setCellValue("B{$row}", $p->product->sku);
            $sheet->setCellValue("C{$row}", $p->quantitySold);
            $sheet->setCellValue("D{$row}", $p->revenue);
            $sheet->setCellValue("E{$row}", $p->cost);
            $sheet->setCellValue("F{$row}", $p->profit);
            $sheet->setCellValue("G{$row}", $p->product->stock_quantity);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/public/product_performance_' . date('Y-m-d') . '.xlsx');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.product-performance', [
            'products' => $this->products,
            'stats' => $this->stats,
            'topSellers' => $this->topSellers,
            'slowMovers' => $this->slowMovers,
        ]);
    }
}
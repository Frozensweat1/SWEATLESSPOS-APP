<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class InventoryReport extends Component
{
    use WithPagination, StoreScoped;

    public $search = '';
    public $categoryFilter = '';
    public $supplierFilter = '';
    public $lowStock = false;
    public $expiringSoon = false;
    public $lowStockThreshold = 10;
    public $expiryDays = 30;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function __construct()
    {
        $this->initStoreId();
    }

    public function updated($propertyName)
    {
        if ($propertyName !== 'perPage') {
            $this->resetPage();
        }
    }

    public function getProductsProperty()
    {
        return Product::where('store_id', $this->getStoreId())
            ->with(['category', 'supplier'])
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->orWhere('barcode', 'like', '%' . $this->search . '%'))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->supplierFilter, fn($query) => $query->where('supplier_id', $this->supplierFilter))
            ->when($this->lowStock, fn($query) => $query->where('stock_quantity', '<=', $this->lowStockThreshold))
            ->when($this->expiringSoon, fn($query) => $query->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays($this->expiryDays))
                ->where('expiry_date', '>', now()))
            ->paginate($this->perPage);
    }

    public function getInventoryStatsProperty()
    {
        $storeId = $this->getStoreId();
        
        $totalProducts = Product::where('store_id', $storeId)->count();
        $totalStock = Product::where('store_id', $storeId)->sum('stock_quantity');
        $totalValue = Product::where('store_id', $storeId)->sum(DB::raw('stock_quantity * price'));
        $totalCost = Product::where('store_id', $storeId)->sum(DB::raw('stock_quantity * cost_price'));
        
        $lowStock = Product::where('store_id', $storeId)
            ->where('stock_quantity', '<=', $this->lowStockThreshold)
            ->where('stock_quantity', '>', 0)
            ->count();
            
        $outOfStock = Product::where('store_id', $storeId)->where('stock_quantity', '<=', 0)->count();
        
        $expiringSoon = Product::where('store_id', $storeId)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays($this->expiryDays))
            ->where('expiry_date', '>', now())
            ->count();

        return [
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'totalValue' => $totalValue,
            'totalCost' => $totalCost,
            'potentialProfit' => $totalValue - $totalCost,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'expiringSoon' => $expiringSoon,
        ];
    }

    public function export($format)
    {
        $products = Product::where('store_id', $this->getStoreId())
            ->with(['category', 'supplier'])
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->supplierFilter, fn($query) => $query->where('supplier_id', $this->supplierFilter))
            ->when($this->lowStock, fn($query) => $query->where('stock_quantity', '<=', $this->lowStockThreshold))
            ->when($this->expiringSoon, fn($query) => $query->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now()->addDays($this->expiryDays)))
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'SKU');
        $sheet->setCellValue('C1', 'Barcode');
        $sheet->setCellValue('D1', 'Category');
        $sheet->setCellValue('E1', 'Supplier');
        $sheet->setCellValue('F1', 'Price');
        $sheet->setCellValue('G1', 'Cost Price');
        $sheet->setCellValue('H1', 'Stock');
        $sheet->setCellValue('I1', 'Stock Value');
        $sheet->setCellValue('J1', 'Expiry Date');
        $sheet->setCellValue('K1', 'Status');

        foreach ($products as $index => $product) {
            $status = $product->stock_quantity <= 0 ? 'Out of Stock' : 
                      ($product->stock_quantity <= $this->lowStockThreshold ? 'Low Stock' : 'In Stock');
            
            $sheet->setCellValue('A' . ($index + 2), $product->name);
            $sheet->setCellValue('B' . ($index + 2), $product->sku);
            $sheet->setCellValue('C' . ($index + 2), $product->barcode);
            $sheet->setCellValue('D' . ($index + 2), $product->category->name ?? '');
            $sheet->setCellValue('E' . ($index + 2), $product->supplier->name ?? '');
            $sheet->setCellValue('F' . ($index + 2), $product->price);
            $sheet->setCellValue('G' . ($index + 2), $product->cost_price);
            $sheet->setCellValue('H' . ($index + 2), $product->stock_quantity);
            $sheet->setCellValue('I' . ($index + 2), $product->stock_quantity * $product->price);
            $sheet->setCellValue('J' . ($index + 2), $product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A');
            $sheet->setCellValue('K' . ($index + 2), $status);
        }

        $fileName = 'inventory_report_' . date('Y-m-d') . '.' . $format;
        $writer = $format === 'csv' ? new Csv($spreadsheet) : new Xlsx($spreadsheet);

        $filePath = storage_path('app/public/' . $fileName);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.inventory-report', [
            'products' => $this->products,
            'stats' => $this->inventoryStats,
            'categories' => \App\Models\Category::where('store_id', $this->getStoreId())->get(),
            'suppliers' => \App\Models\Supplier::where('store_id', $this->getStoreId())->get(),
        ]);
    }
}

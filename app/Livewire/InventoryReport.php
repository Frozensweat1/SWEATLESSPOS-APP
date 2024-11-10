<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class InventoryReport extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $supplierFilter = '';
    public $lowStock = false;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updated($propertyName)
    {
        if ($propertyName !== 'perPage') {
            $this->resetPage();
        }
    }

    public function export($format)
    {
        $products = $this->getProductsProperty();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'SKU');
        $sheet->setCellValue('C1', 'Barcode');
        $sheet->setCellValue('D1', 'Category');
        $sheet->setCellValue('E1', 'Supplier');
        $sheet->setCellValue('F1', 'Price');
        $sheet->setCellValue('G1', 'Cost Price');
        $sheet->setCellValue('H1', 'Stock Quantity');
        $sheet->setCellValue('I1', 'Expiry Date');

        foreach ($products as $index => $product) {
            $sheet->setCellValue('A' . ($index + 2), $product->name);
            $sheet->setCellValue('B' . ($index + 2), $product->sku);
            $sheet->setCellValue('C' . ($index + 2), $product->barcode);
            $sheet->setCellValue('D' . ($index + 2), $product->category->name ?? '');
            $sheet->setCellValue('E' . ($index + 2), $product->supplier->name ?? '');
            $sheet->setCellValue('F' . ($index + 2), $product->price);
            $sheet->setCellValue('G' . ($index + 2), $product->cost_price);
            $sheet->setCellValue('H' . ($index + 2), $product->stock_quantity);
            $sheet->setCellValue('I' . ($index + 2), $product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A');
        }

        $fileName = 'inventory_report.' . $format;
        $writer = $format === 'csv' ? new Csv($spreadsheet) : new Xlsx($spreadsheet);

        $filePath = storage_path('app/public/' . $fileName);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function getProductsProperty()
    {
        return Product::with(['category', 'supplier'])
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->categoryFilter, fn($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->supplierFilter, fn($query) => $query->where('supplier_id', $this->supplierFilter))
            ->when($this->lowStock, fn($query) => $query->where('stock_quantity', '<', 10))
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.inventory-report', [
            'products' => $this->products,
            'categories' => \App\Models\Category::all(),
            'suppliers' => \App\Models\Supplier::all(),
        ]);
    }
}

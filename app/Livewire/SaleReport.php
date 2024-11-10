<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class SaleReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $paymentMethod;
    public $searchTerm;
    public $perPage = 5;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function getSalesProperty()
    {
        $query = Sale::with(['saleDetails' => function ($query) {
            $query->where('is_refunded', false); // Only include saleDetails where is_refunded is false
        }, 'saleDetails.product', 'customer', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        if ($this->paymentMethod) {
            $query->where('payment_method', $this->paymentMethod);
        }

        if ($this->searchTerm) {
            $query->whereHas('customer', function ($q) {
                $q->where('name', 'like', "%{$this->searchTerm}%");
            });
        }

        return $query->paginate($this->perPage);
    }

    public function export($format)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Sale ID');
        $sheet->setCellValue('B1', 'Customer');
        $sheet->setCellValue('C1', 'Product');
        $sheet->setCellValue('D1', 'Quantity');
        $sheet->setCellValue('E1', 'Price per Unit');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Payment Method');
        $sheet->setCellValue('H1', 'Date');

        // Fill data
        $row = 2;
        foreach ($this->sales as $sale) {
            foreach ($sale->saleDetails as $detail) {
                $sheet->setCellValue("A{$row}", $sale->id);
                $sheet->setCellValue("B{$row}", $sale->customer ? $sale->customer->name : 'Guest');
                $sheet->setCellValue("C{$row}", $detail->product->name);
                $sheet->setCellValue("D{$row}", $detail->quantity);
                $sheet->setCellValue("E{$row}", $detail->price_per_unit);
                $sheet->setCellValue("F{$row}", $detail->subtotal);
                $sheet->setCellValue("G{$row}", $sale->payment_method);
                $sheet->setCellValue("H{$row}", $sale->created_at->format('Y-m-d'));
                $row++;
            }
        }

        $fileName = 'sales_report.' . $format;
        $filePath = storage_path('app/public/' . $fileName);

        if ($format === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } elseif ($format === 'csv') {
            $writer = new Csv($spreadsheet);
        } else {
            $writer = new Mpdf($spreadsheet);
        }

        $writer->save($filePath);
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.sale-report', [
            'sales' => $this->sales,
        ]);
    }
}

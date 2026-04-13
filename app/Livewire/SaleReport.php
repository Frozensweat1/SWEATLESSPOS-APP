<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class SaleReport extends Component
{
    use WithPagination, StoreScoped;
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $paymentMethod;
    public $searchTerm;
    public $perPage = 5;

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
    }

    public function getSalesQuery()
    {
        return Sale::where('store_id', $this->getStoreId())
            ->with(['saleDetails' => function ($query) {
                $query->where('is_refunded', false);
            }, 'saleDetails.product', 'customer', 'user'])
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate);
    }

    public function getSalesProperty()
    {
        return $this->getSalesQuery()->paginate($this->perPage);
    }

    public function getSalesSummaryProperty()
    {
        $query = $this->getSalesQuery();
        
        $totalRevenue = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate)
            ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod)))
            ->where('is_refunded', false)
            ->sum('subtotal');

        $totalItems = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
            ->where('created_at', '>=', $this->startDate)
            ->where('created_at', '<=', $this->endDate)
            ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod)))
            ->where('is_refunded', false)
            ->sum('quantity');

        $transactionCount = $query->count();

        return [
            'totalRevenue' => $totalRevenue,
            'totalItems' => $totalItems,
            'transactionCount' => $transactionCount,
            'averageOrder' => $transactionCount > 0 ? $totalRevenue / $transactionCount : 0,
        ];
    }

    public function export($format)
    {
        $query = $this->getSalesQuery();
        
        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->whereHas('customer', fn($sub) => $sub->where('name', 'like', "%{$this->searchTerm}%"))
                  ->orWhere('id', 'like', "%{$this->searchTerm}%")
                  ->orWhereHas('saleDetails.product', fn($sub) => $sub->where('name', 'like', "%{$this->searchTerm}%"));
            });
        }

        $sales = $query->with(['saleDetails.product', 'customer', 'user'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Sale ID');
        $sheet->setCellValue('B1', 'Customer');
        $sheet->setCellValue('C1', 'Product');
        $sheet->setCellValue('D1', 'Quantity');
        $sheet->setCellValue('E1', 'Price per Unit');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Payment Method');
        $sheet->setCellValue('H1', 'Date');
        $sheet->setCellValue('I1', 'Cashier');

        $row = 2;
        foreach ($sales as $sale) {
            foreach ($sale->saleDetails as $detail) {
                $sheet->setCellValue("A{$row}", $sale->id);
                $sheet->setCellValue("B{$row}", $sale->customer ? $sale->customer->name : 'Guest');
                $sheet->setCellValue("C{$row}", $detail->product->name ?? 'N/A');
                $sheet->setCellValue("D{$row}", $detail->quantity);
                $sheet->setCellValue("E{$row}", $detail->price_per_unit);
                $sheet->setCellValue("F{$row}", $detail->subtotal);
                $sheet->setCellValue("G{$row}", $sale->payment_method);
                $sheet->setCellValue("H{$row}", $sale->created_at->format('Y-m-d'));
                $sheet->setCellValue("I{$row}", $sale->user->name ?? 'N/A');
                $row++;
            }
        }

        $fileName = 'sales_report_' . date('Y-m-d') . '.' . $format;
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
            'summary' => $this->salesSummary,
        ]);
    }
}

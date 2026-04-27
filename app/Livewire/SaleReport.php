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
    public $showSaleDetails = false;
    public $selectedSale = null;

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function updatedPaymentMethod()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function getSalesQuery()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Sale::where('store_id', $this->getStoreId())
            ->with(['saleDetails' => function ($query) {
                $query->where('is_refunded', false);
            }, 'saleDetails.product', 'customer', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod))
            ->when($this->searchTerm, function ($q) {
                $term = trim($this->searchTerm);

                $q->where(function ($inner) use ($term) {
                    $inner->where('id', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn($customerQ) => $customerQ->where('name', 'like', "%{$term}%"))
                        ->orWhereHas('saleDetails.product', fn($productQ) => $productQ->where('name', 'like', "%{$term}%"));
                });
            })
            ->orderBy('created_at', 'desc');
    }

    public function getSalesProperty()
    {
        return $this->getSalesQuery()->paginate($this->perPage);
    }

    public function getSalesSummaryProperty()
    {
        $query = $this->getSalesQuery();

        $salesIds = (clone $query)->pluck('id');

        $totalRevenue = SaleDetail::whereIn('sale_id', $salesIds)
            ->where('is_refunded', false)
            ->sum('subtotal');

        $totalItems = SaleDetail::whereIn('sale_id', $salesIds)
            ->where('is_refunded', false)
            ->sum('quantity');

        $transactionCount = $salesIds->count();

        return [
            'totalRevenue' => $totalRevenue,
            'totalItems' => $totalItems,
            'transactionCount' => $transactionCount,
            'averageOrder' => $transactionCount > 0 ? $totalRevenue / $transactionCount : 0,
        ];
    }

    public function export($format)
    {
        $sales = $this->getSalesQuery()->with(['saleDetails.product', 'customer', 'user'])->get();

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

    public function viewSaleDetails($saleId)
    {
        $this->selectedSale = Sale::where('store_id', $this->getStoreId())
            ->with([
                'saleDetails' => fn($q) => $q->where('is_refunded', false)->with('product'),
                'customer',
                'user',
            ])
            ->find($saleId);

        if ($this->selectedSale) {
            $this->showSaleDetails = true;
        }
    }

    public function closeSaleDetails()
    {
        $this->showSaleDetails = false;
        $this->selectedSale = null;
    }

    public function render()
    {
        return view('livewire.sale-report', [
            'sales' => $this->sales,
            'summary' => $this->salesSummary,
        ]);
    }
}

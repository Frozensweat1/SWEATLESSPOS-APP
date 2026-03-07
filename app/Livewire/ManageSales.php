<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Refund;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Illuminate\Support\Facades\Auth; // Add this
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class ManageSales extends Component
{
    use WithFileUploads;
    use WithPagination;
    use StoreScoped;

    public $selectedSales = [];
    public $selectAll = false;
    public $filter = 'daily';
    public $startDate;
    public $endDate;
    public $searchTerm = '';
    public $categoryFilter = '';
    public $paymentMethodFilter = '';
    public $perPage = 5;
    public $pageTitle, $componentName;
    protected $paginationTheme = 'bootstrap';
    public $isRefundMode = false, $sdID, $sale_id, $product_id, $quantity, $subtotal, $reason;
    public $viewingSaleId = null;
    public $viewingSale = null;
    public $viewingCustomer = null;
    public $viewingSaleBarcode = null;
    public $showViewReceipt = false;
    public $settings;
    public function mount()
    {
        $this->initStoreId();
        $this->pageTitle = 'Manage Sales';
        $this->componentName = 'Sales';
        $this->loadDateRange();
        $this->settings = \App\Models\Setting::first() ?? new \App\Models\Setting([
            'site_name' => config('app.name', 'Sweatless POS'),
            'receipt_header' => 'In God We Trust!',
            'receipt_footer' => 'Thank you for your purchase!',
        ]);
    }

    public function openModal()
    {
        $this->isRefundMode = true;
    }

    public function closeModal()
    {
        $this->isRefundMode = false;
        $this->resetUI();
    }

    public function Refund($id)
    {
        $this->isRefundMode = true;
        $record = SaleDetail::find($id, [
            'id',
            'sale_id',
            'product_id',
            'quantity',
            'subtotal',

        ]);
        $this->sdID = $record->id;
        $this->sale_id = $record->sale_id;
        $this->product_id = $record->product_id;
        $this->quantity = $record->quantity;
        $this->subtotal = $record->subtotal;
        $this->openModal();
    }

    public function processRefund()
    {
        // Start a database transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Create the refund record
            $refund = Refund::create([
                'sale_id' => $this->sale_id,
                'sale_detail_id' => $this->sdID,
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'refund_amount' => $this->subtotal,
                'reason' => $this->reason,
                'user_id' => Auth::id(),
                'store_id' => $this->getStoreId()
            ]);

            // Increase stock quantity of the refunded product
            $product = Product::where('store_id', $this->getStoreId())->find($this->product_id);
            if ($product) {
                $product->increment('stock_quantity', $this->quantity);
            }

            // Update sale detail to indicate the item has been refunded
            $saleDetail = SaleDetail::find($this->sdID);
            if ($saleDetail) {
                $saleDetail->update(['is_refunded' => true]);
            }

            // Log Notification
            $this->logNotification('Refund Processed', "Sold Item '{$refund->sale_detail_id}' returned.", 'info');

            // Commit the transaction
            DB::commit();

            // Reset the UI and show success message
            $this->closeModal();
            LivewireAlert::title('Success')
                ->text('Refund Processed Successfully')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('An error occurred while processing the refund. Please try again.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function updatedSelectAll($value)
    {
        $this->selectedSales = $value ? $this->getSalesProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedSales()
    {
        $this->selectAll = count($this->selectedSales) === $this->getSalesProperty()->count();
    }

    public function updatedFilter()
    {
        $this->loadDateRange();
    }

    public function loadDateRange()
    {
        switch ($this->filter) {
            case 'daily':
                $this->startDate = Carbon::today();
                $this->endDate = Carbon::tomorrow();
                break;
            case 'weekly':
                $this->startDate = Carbon::now()->startOfWeek();
                $this->endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $this->startDate = Carbon::now()->startOfMonth();
                $this->endDate = Carbon::now()->endOfMonth();
                break;
            case 'yearly':
                $this->startDate = Carbon::now()->startOfYear();
                $this->endDate = Carbon::now()->endOfYear();
                break;
            default:
                $this->startDate = null;
                $this->endDate = null;
                break;
        }
    }

    public function getSalesProperty()
    {
        $query = Sale::where('store_id', $this->getStoreId())
            ->with(['customer', 'saleDetails' => function ($query) {
                $query->where('is_refunded', false);
            }, 'saleDetails.product'])
            ->when($this->startDate && $this->endDate, function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->searchTerm, function ($q) {
                $q->whereHas('customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->when($this->paymentMethodFilter, function ($q) {
                $q->where('payment_method', $this->paymentMethodFilter);
            })
            ->orderBy('created_at', 'desc');

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
        $sheet->setCellValue('E1', 'Total Amount');
        $sheet->setCellValue('F1', 'Payment Method');
        $sheet->setCellValue('G1', 'Date');

        // Track row index separately for sale details
        $row = 2;

        foreach ($this->sales as $sale) {
            foreach ($sale->saleDetails as $detail) {
                $sheet->setCellValue("A{$row}", $sale->id);
                $sheet->setCellValue("B{$row}", $sale->customer ? $sale->customer->name : 'Guest');
                $sheet->setCellValue("C{$row}", $detail->product->name);
                $sheet->setCellValue("D{$row}", $detail->quantity);
                $sheet->setCellValue("E{$row}", $detail->subtotal);
                $sheet->setCellValue("F{$row}", $sale->payment_method);
                $sheet->setCellValue("G{$row}", $sale->created_at->format('Y-m-d'));

                // Move to the next row for each sale detail
                $row++;
            }
        }

        $fileName = 'sales.' . $format;

        if ($format === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
        } elseif ($format === 'csv') {
            $writer = new Csv($spreadsheet);
        } elseif ($format === 'pdf') {
            $writer = new Mpdf($spreadsheet);
        } else {
            $writer = new Xlsx($spreadsheet);
        }

        // Save the file and download
        $filePath = storage_path('app/public/' . $fileName);
        $writer->save($filePath);

        // Log the export notification
        $this->logNotification('Export Sales', 'Sales data exported successfully as ' . strtoupper($format) . '.', 'info');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function bulkDelete()
    {
        LivewireAlert::title('Are you sure you want to delete?')
            ->warning()
            ->onConfirm('confirmedDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }
    public function confirmedDeletion()
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Fetch the selected sales and their details from the store
            $sales = Sale::where('store_id', $this->getStoreId())
                ->with('saleDetails')
                ->whereIn('id', $this->selectedSales)
                ->get();

            // Delete sale details for each selected sale
            foreach ($sales as $sale) {
                foreach ($sale->saleDetails as $detail) {
                    $detail->delete();
                }
            }

            // Now delete the sales themselves
            Sale::where('store_id', $this->getStoreId())->whereIn('id', $this->selectedSales)->delete();

            // Log notification for deletion
            $this->logNotification('Delete Sales', 'Selected sales and their details deleted successfully.', 'warning');

            // Commit the transaction
            DB::commit();

            LivewireAlert::title('Success')
                ->text('Selected sales and their details deleted successfully')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
            $this->selectedSales = [];
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('An error occurred while deleting selected sales. Please try again.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    protected $listeners = [
        'confirmedDeletion'
    ];
    protected function logNotification($title, $message, $type)
    {
        Notification::create([
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
            'read_at' => null,
        ]);
    }
    public function resetUI()
    {
        $this->reset(['sale_id', 'product_id', 'quantity', 'subtotal', 'sdID', 'reason']);
    }

    public function viewReceipt($saleId)
    {
        $this->viewingSaleId = $saleId;
        $this->viewingSale = Sale::where('store_id', $this->getStoreId())
            ->with(['customer', 'saleDetails.product'])
            ->find($saleId);

        if ($this->viewingSale) {
            $this->viewingCustomer = $this->viewingSale->customer;

            // Generate barcode for viewing sale
            $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorPNG();
            $this->viewingSaleBarcode = base64_encode($barcodeGenerator->getBarcode($this->viewingSale->id, $barcodeGenerator::TYPE_CODE_128));

            $this->showViewReceipt = true;
        }
    }

    public function closeViewReceipt()
    {
        $this->showViewReceipt = false;
        $this->viewingSaleId = null;
        $this->viewingSale = null;
        $this->viewingCustomer = null;
        $this->viewingSaleBarcode = null;
    }
    public function render()
    {
        return view('livewire.manage-sales', [
            'sales' => $this->sales,
        ]);
    }
}

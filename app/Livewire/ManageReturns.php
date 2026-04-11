<?php

namespace App\Livewire;

use App\Models\Refund;
use App\Models\Product;
use App\Models\User;
use App\Services\RefundService;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\StoreScoped;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageReturns extends Component
{
    use WithPagination, StoreScoped;

    public $products;
    public $users;
    public $selectedRefunds = [];
    public $selectAll = false;
    public $filters = [
        'product_id' => null,
        'user_id' => null,
        'start_date' => null,
        'end_date' => null,
    ];

    protected ?RefundService $refundService = null;

    public function mount()
    {
        $this->initStoreId();
        $this->refundService = app(RefundService::class);
        $this->products = Product::where('store_id', $this->getStoreId())->get();
        $this->users = User::role(['admin', 'super-admin'])->get();
    }

    protected function getRefundService(): RefundService
    {
        if ($this->refundService === null) {
            $this->refundService = app(RefundService::class);
        }
        return $this->refundService;
    }

    public function confirmBulkDelete()
    {
        if (count($this->selectedRefunds) > 0) {
            LivewireAlert::title('Are you sure?')
                ->html('You are about to delete selected refunds.')
                ->warning()
                ->onConfirm('bulkDeleteRefunds')
                ->withConfirmButton('Yes, Delete')
                ->withCancelButton('Cancel')
                ->show();
        } else {
            LivewireAlert::warning('No refunds selected.')
                ->toast()
                ->position('top-end')
                ->show();
        }
    }
    public function bulkDeleteProducts()
    {
        try {
            foreach ($this->selectedRefunds as $refundId) {
                $this->getRefundService()->cancelRefund($this->getStoreId(), $refundId);
            }
            $this->selectedRefunds = [];
            LivewireAlert::title('Success')
                ->text('Selected refunds deleted successfully.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text($e->getMessage() ?: 'Failed to delete refunds.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function updatedSelectAll($value)
    {
        $this->selectedRefunds = $value ? $this->getRefundsProperty()->pluck('id')->toArray() : [];
    }

    public function updatedSelectedRefunds()
    {
        $this->selectAll = count($this->selectedRefunds) === $this->getRefundsProperty()->count();
    }
    public function getRefundsProperty()
    {
        return $this->getRefundService()->getRefunds($this->getStoreId(), $this->filters, 10);
    }

    public function render()
    {
        return view('livewire.manage-returns', [
            'refunds' => $this->refunds,
            'products' => $this->products,
            'users' => $this->users,
        ]);
    }

    public function exportRefunds()
    {
        $refunds = Refund::with(['product', 'user'])
            ->filter($this->filters)
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Sale ID')
            ->setCellValue('B1', 'Sale Detail ID')
            ->setCellValue('C1', 'Product')
            ->setCellValue('D1', 'Quantity')
            ->setCellValue('E1', 'Refund Amount')
            ->setCellValue('F1', 'Reason')
            ->setCellValue('G1', 'User')
            ->setCellValue('H1', 'Created At');

        foreach ($refunds as $index => $refund) {
            $sheet->setCellValue("A" . ($index + 2), $refund->sale_id)
                ->setCellValue("B" . ($index + 2), $refund->sale_detail_id)
                ->setCellValue("C" . ($index + 2), $refund->product->name ?? 'N/A')
                ->setCellValue("D" . ($index + 2), $refund->quantity)
                ->setCellValue("E" . ($index + 2), $refund->refund_amount)
                ->setCellValue("F" . ($index + 2), $refund->reason)
                ->setCellValue("G" . ($index + 2), $refund->user->name ?? 'N/A')
                ->setCellValue("H" . ($index + 2), $refund->created_at);
        }

        $filePath = 'exports/refunds_' . now()->timestamp . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save(Storage::path($filePath));

        return response()->download(Storage::path($filePath))->deleteFileAfterSend(true);
    }
    protected $listeners = [
        'bulkDeleteRefunds'
    ];
    public function deleteSelected()
    {
        Refund::whereIn('id', $this->selectedRefunds)->delete();
        $this->selectedRefunds = [];
    }
}

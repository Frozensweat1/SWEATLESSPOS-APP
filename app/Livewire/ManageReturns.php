<?php

namespace App\Livewire;

use App\Models\Refund;
use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class ManageReturns extends Component
{
    use WithPagination;
    use LivewireAlert;

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

    public function mount()
    {
        $this->products = Product::all();
        $this->users = User::role(['admin', 'super-admin'])->get();
    }
    public function confirmBulkDelete()
    {
        if (count($this->selectedRefunds) > 0) {
            $this->alert('warning', 'Are you sure?', [
                'text' => 'You are about to delete selected refunds.',
                'showConfirmButton' => true,
                'confirmButtonText' => 'Yes, Delete',
                'onConfirmed' => 'bulkDeleteRefunds',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
            ]);
        } else {
            $this->alert('warning', 'No refunds selected.');
        }
    }
    public function bulkDeleteProducts()
    {
        Product::whereIn('id', $this->selectedRefunds)->delete();
        $this->selectedRefunds = []; // Clear selected products
        $this->alert('success', 'Selected refunds deleted successfully.');
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
        $query = Refund::query()->with(['product', 'user'])
            ->when($this->filters['product_id'], function ($q) {
                $q->where('product_id', $this->filters['product_id']);
            })
            ->when($this->filters['user_id'], function ($q) {
                $q->where('user_id', $this->filters['user_id']);
            })
            ->when($this->filters['start_date'], function ($q) {
                $q->where('created_at', '>=', Carbon::parse($this->filters['start_date'])->startOfDay());
            })
            ->when($this->filters['end_date'], function ($q) {
                $q->where('created_at', '<=', Carbon::parse($this->filters['end_date'])->endOfDay());
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate(10);
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

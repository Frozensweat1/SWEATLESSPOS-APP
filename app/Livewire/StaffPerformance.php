<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Layout('layouts.app')]
class StaffPerformance extends Component
{
    use StoreScoped;

    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function getStaffStatsProperty()
    {
        return User::whereHas('sales', fn($q) => $q->where('store_id', $this->getStoreId())
            ->whereBetween('created_at', [$this->startDate, $this->endDate]))
            ->get()
            ->map(function ($user) {
                $sales = Sale::where('store_id', $this->getStoreId())
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate]);

                $transactionCount = (clone $sales)->count();
                $totalRevenue = (clone $sales)->sum('total_amount');
                $totalItems = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate]))
                    ->where('is_refunded', false)
                    ->sum('quantity');

                $refunds = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->where('user_id', $user->id)
                    ->whereBetween('created_at', [$this->startDate, $this->endDate]))
                    ->where('is_refunded', true)
                    ->sum('subtotal');

                return (object) [
                    'user' => $user,
                    'transactionCount' => $transactionCount,
                    'totalRevenue' => $totalRevenue,
                    'totalItems' => $totalItems,
                    'refunds' => $refunds,
                    'avgTransaction' => $transactionCount > 0 ? $totalRevenue / $transactionCount : 0,
                ];
            })
            ->sortByDesc('totalRevenue');
    }

    public function getSummaryProperty()
    {
        $allStats = $this->staffStats;
        
        return [
            'totalStaff' => $allStats->count(),
            'totalRevenue' => $allStats->sum('totalRevenue'),
            'totalTransactions' => $allStats->sum('transactionCount'),
            'topPerformer' => $allStats->first(),
        ];
    }

    public function export()
    {
        $staff = $this->staffStats;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Staff Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Transactions');
        $sheet->setCellValue('D1', 'Total Revenue');
        $sheet->setCellValue('E1', 'Items Sold');
        $sheet->setCellValue('F1', 'Refunds');
        $sheet->setCellValue('G1', 'Avg Transaction');

        $row = 2;
        foreach ($staff as $s) {
            $sheet->setCellValue("A{$row}", $s->user->name);
            $sheet->setCellValue("B{$row}", $s->user->email);
            $sheet->setCellValue("C{$row}", $s->transactionCount);
            $sheet->setCellValue("D{$row}", $s->totalRevenue);
            $sheet->setCellValue("E{$row}", $s->totalItems);
            $sheet->setCellValue("F{$row}", $s->refunds);
            $sheet->setCellValue("G{$row}", $s->avgTransaction);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/public/staff_performance_' . date('Y-m-d') . '.xlsx');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.staff-performance', [
            'staffStats' => $this->staffStats,
            'summary' => $this->summary,
        ]);
    }
}
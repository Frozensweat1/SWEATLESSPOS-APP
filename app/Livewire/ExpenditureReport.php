<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Expenditure;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Layout('layouts.app')]
class ExpenditureReport extends Component
{
    use WithPagination, StoreScoped;

    public $startDate;
    public $endDate;
    public $categoryFilter = '';
    public $perPage = 15;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->initStoreId();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function getExpendituresProperty()
    {
        return Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', [$this->startDate, $this->endDate])
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->orderBy('expenditure_date', 'desc')
            ->paginate($this->perPage);
    }

    public function getSummaryProperty()
    {
        $query = Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', [$this->startDate, $this->endDate])
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter));

        $total = (clone $query)->sum('amount');
        $count = (clone $query)->count();
        $avg = $count > 0 ? $total / $count : 0;

        $byCategory = (clone $query)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'total' => $total,
            'count' => $count,
            'average' => $avg,
            'byCategory' => $byCategory,
        ];
    }

    public function export()
    {
        $expenditures = Expenditure::where('store_id', $this->getStoreId())
            ->whereBetween('expenditure_date', [$this->startDate, $this->endDate])
            ->when($this->categoryFilter, fn($q) => $q->where('category', $this->categoryFilter))
            ->with('user')
            ->orderBy('expenditure_date', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Date');
        $sheet->setCellValue('B1', 'Description');
        $sheet->setCellValue('C1', 'Category');
        $sheet->setCellValue('D1', 'Amount');
        $sheet->setCellValue('E1', 'User');
        $sheet->setCellValue('F1', 'Created At');

        $row = 2;
        foreach ($expenditures as $e) {
            $sheet->setCellValue("A{$row}", $e->expenditure_date->format('Y-m-d'));
            $sheet->setCellValue("B{$row}", $e->description);
            $sheet->setCellValue("C{$row}", $e->category ?? 'N/A');
            $sheet->setCellValue("D{$row}", $e->amount);
            $sheet->setCellValue("E{$row}", $e->user->name ?? 'N/A');
            $sheet->setCellValue("F{$row}", $e->created_at->format('Y-m-d H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/public/expenditure_report_' . date('Y-m-d') . '.xlsx');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.expenditure-report', [
            'expenditures' => $this->expenditures,
            'summary' => $this->summary,
            'categories' => Expenditure::where('store_id', $this->getStoreId())
                ->distinct()
                ->pluck('category')
                ->filter(),
        ]);
    }
}
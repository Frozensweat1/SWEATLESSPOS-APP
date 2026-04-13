<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SaleDetail;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

#[Layout('layouts.app')]
class CategoryPerformance extends Component
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

    public function getCategoryStatsProperty()
    {
        return Category::where('store_id', $this->getStoreId())
            ->get()
            ->map(function ($category) {
                $sales = SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId())
                    ->whereBetween('created_at', [$this->startDate, $this->endDate]))
                    ->whereHas('product', fn($q) => $q->where('category_id', $category->id))
                    ->where('is_refunded', false);

                $quantitySold = (clone $sales)->sum('quantity');
                $revenue = (clone $sales)->sum('subtotal');
                $cost = (clone $sales)
                    ->join('products', 'sale_details.product_id', '=', 'products.id')
                    ->where('products.category_id', $category->id)
                    ->sum(DB::raw('sale_details.quantity * products.cost_price'));
                $profit = $revenue - $cost;

                return (object) [
                    'category' => $category,
                    'quantitySold' => $quantitySold,
                    'revenue' => $revenue,
                    'cost' => $cost,
                    'profit' => $profit,
                ];
            })
            ->filter(fn($c) => $c->revenue > 0)
            ->sortByDesc('revenue');
    }

    public function getSummaryProperty()
    {
        $stats = $this->categoryStats;
        
        return [
            'totalCategories' => $stats->count(),
            'totalRevenue' => $stats->sum('revenue'),
            'totalProfit' => $stats->sum('profit'),
            'topCategory' => $stats->first(),
        ];
    }

    public function export()
    {
        $categories = $this->categoryStats;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Category');
        $sheet->setCellValue('B1', 'Qty Sold');
        $sheet->setCellValue('C1', 'Revenue');
        $sheet->setCellValue('D1', 'Cost');
        $sheet->setCellValue('E1', 'Profit');
        $sheet->setCellValue('F1', 'Margin %');

        $row = 2;
        foreach ($categories as $c) {
            $margin = $c->revenue > 0 ? ($c->profit / $c->revenue) * 100 : 0;
            $sheet->setCellValue("A{$row}", $c->category->name);
            $sheet->setCellValue("B{$row}", $c->quantitySold);
            $sheet->setCellValue("C{$row}", $c->revenue);
            $sheet->setCellValue("D{$row}", $c->cost);
            $sheet->setCellValue("E{$row}", $c->profit);
            $sheet->setCellValue("F{$row}", number_format($margin, 1));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/public/category_performance_' . date('Y-m-d') . '.xlsx');
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.category-performance', [
            'categoryStats' => $this->categoryStats,
            'summary' => $this->summary,
        ]);
    }
}
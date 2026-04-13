<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Traits\StoreScoped;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class CustomerReport extends Component
{
    use WithPagination, StoreScoped;

    public $search = '';
    public $sortField = 'total_spent';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function __construct()
    {
        $this->initStoreId();
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'desc') ? 'asc' : 'desc';
        $this->sortField = $field;
    }

    public function getCustomersProperty()
    {
        $allCustomers = Customer::where('store_id', $this->getStoreId())
            ->with(['sales' => function ($query) {
                $query->with(['saleDetails' => function ($detailQuery) {
                    $detailQuery->where('is_refunded', false);
                }]);
            }])
            ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->get()
            ->map(function ($customer) {
                $totalSpent = $customer->sales->flatMap(fn($s) => $s->saleDetails)->sum('subtotal');
                $totalOrders = $customer->sales->count();
                $lastPurchase = $customer->sales->max('created_at');
                $avgOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
                
                $tier = 'New';
                if ($totalSpent >= 5000) $tier = 'VIP';
                elseif ($totalSpent >= 2000) $tier = 'Premium';
                elseif ($totalSpent >= 500) $tier = 'Regular';

                return (object) [
                    'customer' => $customer,
                    'totalSpent' => $totalSpent,
                    'totalOrders' => $totalOrders,
                    'lastPurchase' => $lastPurchase,
                    'avgOrderValue' => $avgOrderValue,
                    'tier' => $tier,
                ];
            })
            ->sortByDesc($this->sortField === 'total_spent' ? 'totalSpent' : ($this->sortField === 'total_orders' ? 'totalOrders' : 'customer.created_at'))
            ->when($this->sortDirection === 'asc', fn($c) => $c->reverse());

        $page = request()->query('page', 1);
        
        return new LengthAwarePaginator(
            $allCustomers->forPage($page, $this->perPage)->values(),
            $allCustomers->count(),
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function getTotalCustomersProperty()
    {
        return Customer::where('store_id', $this->getStoreId())->count();
    }

    public function getTotalRevenueProperty()
    {
        return SaleDetail::whereHas('sale', fn($q) => $q->where('store_id', $this->getStoreId()))
            ->where('is_refunded', false)
            ->sum('subtotal');
    }

    public function getCustomerStatsProperty()
    {
        $customers = $this->customers;
        $totalSpent = $customers->sum('totalSpent');
        $totalOrders = $customers->sum('totalOrders');
        
        return [
            'totalCustomers' => $this->totalCustomers,
            'totalRevenue' => $this->totalRevenue,
            'avgCustomerValue' => $this->totalCustomers > 0 ? $this->totalRevenue / $this->totalCustomers : 0,
        ];
    }

    public function export($format)
    {
        $customers = Customer::where('store_id', $this->getStoreId())
            ->with(['sales.saleDetails'])
            ->get()
            ->map(function ($customer) {
                $totalSpent = $customer->sales->flatMap(fn($s) => $s->saleDetails)->where('is_refunded', false)->sum('subtotal');
                $totalOrders = $customer->sales->count();
                $lastPurchase = $customer->sales->max('created_at');
                
                $tier = 'New';
                if ($totalSpent >= 5000) $tier = 'VIP';
                elseif ($totalSpent >= 2000) $tier = 'Premium';
                elseif ($totalSpent >= 500) $tier = 'Regular';

                return [
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'total_orders' => $totalOrders,
                    'total_spent' => $totalSpent,
                    'avg_order_value' => $totalOrders > 0 ? $totalSpent / $totalOrders : 0,
                    'tier' => $tier,
                    'last_purchase' => $lastPurchase ? $lastPurchase->format('Y-m-d') : 'N/A',
                ];
            });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Phone');
        $sheet->setCellValue('D1', 'Total Orders');
        $sheet->setCellValue('E1', 'Total Spent');
        $sheet->setCellValue('F1', 'Avg Order Value');
        $sheet->setCellValue('G1', 'Tier');
        $sheet->setCellValue('H1', 'Last Purchase');

        $row = 2;
        foreach ($customers as $c) {
            $sheet->setCellValue("A{$row}", $c['name']);
            $sheet->setCellValue("B{$row}", $c['email']);
            $sheet->setCellValue("C{$row}", $c['phone']);
            $sheet->setCellValue("D{$row}", $c['total_orders']);
            $sheet->setCellValue("E{$row}", $c['total_spent']);
            $sheet->setCellValue("F{$row}", $c['avg_order_value']);
            $sheet->setCellValue("G{$row}", $c['tier']);
            $sheet->setCellValue("H{$row}", $c['last_purchase']);
            $row++;
        }

        $fileName = 'customer_report_' . date('Y-m-d') . '.' . $format;
        $filePath = storage_path('app/public/' . $fileName);
        $writer = $format === 'csv' ? new Csv($spreadsheet) : new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.customer-report', [
            'customers' => $this->customers,
            'stats' => $this->customerStats,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use App\Models\Customer;
use App\Traits\StoreScoped;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CustomerReportDetail extends Component
{
    use WithPagination, StoreScoped;

    public $customerId;
    public $customer;
    public $startDate;
    public $endDate;
    public $perPage = 10; // Default items per page

    protected $paginationTheme = 'bootstrap';

    public function mount($id)
    {
        $this->initStoreId();
        $this->customerId = $id;
        $this->customer = Customer::where('store_id', $this->getStoreId())->findOrFail($id);
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $sales = Sale::where('store_id', $this->getStoreId())
            ->with(['saleDetails' => function ($query) {
                $query->where('is_refunded', false)
                    ->with('product');
            }])
            ->where('customer_id', $this->customerId)
            ->when($this->startDate, function ($query) {
                $query->whereDate('created_at', '>=', Carbon::parse($this->startDate));
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('created_at', '<=', Carbon::parse($this->endDate));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.customer-report-detail', [
            'sales' => $sales,
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleDetail;
use Livewire\Attributes\Layout;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class CustomerReport extends Component
{
    use WithPagination, LivewireAlert;

    public $search = '';
    public $sortField = 'created_at'; // Default sort
    public $sortDirection = 'desc';
    public $perPage = 10; // Number of items per page

    protected $paginationTheme = 'bootstrap';

    // Sort direction toggle
    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'desc') ? 'asc' : 'desc';
        $this->sortField = $field;
    }

    public function render()
    {
        $customers = Customer::with(['sales' => function ($query) {
            $query->with(['saleDetails' => function ($detailQuery) {
                $detailQuery->where('is_refunded', false); // Only include non-refunded items
            }])
                ->withSum('saleDetails as total_amount', 'subtotal');
        }])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.customer-report', [
            'customers' => $customers,
        ]);
    }
}

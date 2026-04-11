<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\User;
use App\Traits\StoreScoped;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageStockAdjustments extends Component
{
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap';

    public $componentName = 'Stock Adjustments';
    public $pageTitle = 'Audit Log';

    public $product_id;
    public $adjustment_type = 'increase';
    public $quantity;
    public $reason;
    public $notes;

    public $productFilter = '';
    public $typeFilter = '';
    public $startDate;
    public $endDate;
    public $search = '';

    public $products = [];
    public $users = [];

    protected function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:increase,decrease,set',
            'quantity' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function mount()
    {
        $this->initStoreId();
        $this->products = Product::where('store_id', $this->getStoreId())
            ->orderBy('name')
            ->get(['id', 'name', 'stock_quantity']);
        $this->users = User::orderBy('name')->get(['id', 'name']);
    }

    public function applyAdjustment()
    {
        $this->validate();

        $product = Product::where('store_id', $this->getStoreId())->find($this->product_id);
        if (!$product) {
            LivewireAlert::title('Error')
                ->text('Product not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $before = (int) $product->stock_quantity;
        $qty = (int) $this->quantity;

        if ($this->adjustment_type === 'increase') {
            $after = $before + $qty;
        } elseif ($this->adjustment_type === 'decrease') {
            $after = $before - $qty;
            if ($after < 0) {
                LivewireAlert::title('Error')
                    ->text('Quantity cannot go below zero.')
                    ->error()
                    ->toast()
                    ->position('top-end')
                    ->show();
                return;
            }
        } else { // set
            $after = $qty;
        }

        $changeQty = $after - $before;

        $product->update(['stock_quantity' => $after]);

        StockAdjustment::create([
            'store_id' => $this->getStoreId(),
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $this->adjustment_type,
            'quantity_before' => $before,
            'quantity_after' => $after,
            'change_qty' => $changeQty,
            'reason' => $this->reason,
            'notes' => $this->notes,
        ]);

        $this->reset(['product_id', 'adjustment_type', 'quantity', 'reason', 'notes']);
        $this->adjustment_type = 'increase';

        LivewireAlert::title('Success')
            ->text('Stock adjustment saved.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function render()
    {
        $query = StockAdjustment::where('store_id', $this->getStoreId())
            ->with(['product', 'user'])
            ->when($this->productFilter, fn($q) => $q->where('product_id', $this->productFilter))
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('reason', 'like', '%' . $this->search . '%')
                        ->orWhere('notes', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.manage-stock-adjustments', [
            'adjustments' => $query->paginate(15),
        ]);
    }
}


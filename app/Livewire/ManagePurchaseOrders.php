<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use App\Traits\StoreScoped;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManagePurchaseOrders extends Component
{
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap';

    public $componentName = 'Purchase Orders';
    public $pageTitle = 'Supplier Orders';

    public $search = '';
    public $statusFilter = '';
    public $supplierFilter = '';
    public $startDate;
    public $endDate;
    public $perPage = 10;

    public $selectedOrder;
    public $showViewModal = false;

    public function mount()
    {
        $this->initStoreId();
    }

    public function updated($propertyName)
    {
        if ($propertyName !== 'perPage') {
            $this->resetPage();
        }
    }

    public function viewOrder($id)
    {
        $this->selectedOrder = PurchaseOrder::where('store_id', $this->getStoreId())
            ->with(['supplier', 'user', 'items.product'])
            ->find($id);

        if (!$this->selectedOrder) {
            LivewireAlert::title('Error')
                ->text('Purchase order not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedOrder = null;
    }

    public function updateStatus($id, $status)
    {
        $allowed = ['draft', 'sent', 'received', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return;
        }

        $order = PurchaseOrder::where('store_id', $this->getStoreId())
            ->with('items.product')
            ->find($id);
        if (!$order) {
            LivewireAlert::title('Error')
                ->text('Purchase order not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        DB::beginTransaction();

        try {
            $previousStatus = $order->status;

            $order->update([
                'status' => $status,
                'user_id' => $order->user_id ?? Auth::id(),
            ]);

            if ($status === 'received' && $previousStatus !== 'received') {
                foreach ($order->items as $item) {
                    if (!$item->product) {
                        continue;
                    }

                    $product = Product::where('store_id', $this->getStoreId())->find($item->product_id);
                    if (!$product) {
                        continue;
                    }

                    $beforeQty = (int) $product->stock_quantity;
                    $afterQty = $beforeQty + (int) $item->quantity;

                    $product->update(['stock_quantity' => $afterQty]);

                    StockAdjustment::create([
                        'store_id' => $this->getStoreId(),
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'po_received',
                        'quantity_before' => $beforeQty,
                        'quantity_after' => $afterQty,
                        'change_qty' => $afterQty - $beforeQty,
                        'reason' => 'PO ' . $order->order_number,
                        'notes' => 'Received from supplier ' . ($order->supplier?->name ?? 'N/A'),
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('Failed to update purchase order status.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        LivewireAlert::title('Success')
            ->text('Purchase order status updated.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function render()
    {
        $query = PurchaseOrder::where('store_id', $this->getStoreId())
            ->with(['supplier', 'user'])
            ->withCount('items')
            ->when($this->search, fn($q) => $q->where('order_number', 'like', '%' . $this->search . '%'))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->supplierFilter, fn($q) => $q->where('supplier_id', $this->supplierFilter))
            ->when($this->startDate, fn($q) => $q->whereDate('order_date', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('order_date', '<=', $this->endDate))
            ->orderBy('order_date', 'desc');

        return view('livewire.manage-purchase-orders', [
            'orders' => $query->paginate($this->perPage),
            'suppliers' => Supplier::where('store_id', $this->getStoreId())->orderBy('name')->get(),
        ]);
    }
}

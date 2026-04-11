<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Setting;
use App\Models\Supplier;
use App\Traits\StoreScoped;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageReorderAlerts extends Component
{
    use WithPagination;
    use StoreScoped;

    protected $paginationTheme = 'bootstrap';

    public $componentName = 'Reorder Alerts';
    public $pageTitle = 'Low Stock';

    public $search = '';
    public $supplierFilter = '';
    public $categoryFilter = '';
    public $perPage = 10;
    public $lowStockThreshold = 10;

    public function mount()
    {
        $this->initStoreId();
        $this->lowStockThreshold = (int) (Setting::first()?->low_stock_threshold ?? 10);
    }

    public function updated($propertyName)
    {
        if ($propertyName !== 'perPage') {
            $this->resetPage();
        }
    }

    public function createPurchaseOrder($supplierId)
    {
        $supplier = Supplier::where('store_id', $this->getStoreId())->find($supplierId);
        if (!$supplier) {
            LivewireAlert::title('Error')
                ->text('Supplier not found.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        $products = Product::where('store_id', $this->getStoreId())
            ->where('supplier_id', $supplierId)
            ->where('stock_quantity', '<', $this->lowStockThreshold)
            ->get();

        if ($products->isEmpty()) {
            LivewireAlert::title('Info')
                ->text('No low stock items for this supplier.')
                ->info()
                ->toast()
                ->position('top-end')
                ->show();
            return;
        }

        DB::beginTransaction();

        try {
            $order = PurchaseOrder::create([
                'store_id' => $this->getStoreId(),
                'supplier_id' => $supplierId,
                'user_id' => Auth::id(),
                'order_number' => 'PO-TMP-' . uniqid(),
                'status' => 'draft',
                'order_date' => now(),
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($products as $product) {
                $reorderQty = max(0, $this->lowStockThreshold - (int) $product->stock_quantity);
                if ($reorderQty < 1) {
                    continue;
                }

                $unitCost = (float) $product->cost_price;
                $lineTotal = $unitCost * $reorderQty;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $reorderQty,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $order->update([
                'order_number' => 'PO-' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
                'total_amount' => $total,
            ]);

            DB::commit();

            LivewireAlert::title('Success')
                ->text("Purchase order created for {$supplier->name}.")
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')
                ->text('Failed to create purchase order.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    public function render()
    {
        $query = Product::where('store_id', $this->getStoreId())
            ->with(['supplier', 'category'])
            ->where('stock_quantity', '<', $this->lowStockThreshold)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->supplierFilter, fn($q) => $q->where('supplier_id', $this->supplierFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('stock_quantity', 'asc');

        return view('livewire.manage-reorder-alerts', [
            'products' => $query->paginate($this->perPage),
            'suppliers' => Supplier::where('store_id', $this->getStoreId())->orderBy('name')->get(),
            'categories' => \App\Models\Category::where('store_id', $this->getStoreId())->orderBy('name')->get(),
        ]);
    }
}

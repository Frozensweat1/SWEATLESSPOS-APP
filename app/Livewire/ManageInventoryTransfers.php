<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use App\Models\Product;
use App\Models\Store;
use App\Traits\StoreScoped;
use App\Jobs\ProcessInventoryTransfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class ManageInventoryTransfers extends Component
{
    use WithPagination, WithFileUploads, StoreScoped;

    public $tab = 'pending'; // pending, in_transit, received, all
    public $showModal = false;
    public $showReceiveModal = false;
    public $showDetailModal = false;
    public $editingTransferId = null;

    // Create/Edit form
    public $toStoreId = null;
    public $transferNotes = '';
    public $selectedProducts = [];
    public $searchProduct = '';
    public $availableProducts = [];

    // Receive form
    public $receivingTransferId = null;
    public $receiveItems = [];

    // Detail view
    public $detailTransfer = null;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->initStoreId();
    }

    public function render()
    {
        $query = InventoryTransfer::where('store_id', $this->getStoreId())
            ->with(['fromStore', 'toStore', 'requestedBy', 'items.product']);

        if ($this->tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->tab === 'in_transit') {
            $query->where('status', 'in_transit');
        } elseif ($this->tab === 'received') {
            $query->where('status', 'received');
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate(10);

        $stores = Store::where('id', '!=', $this->getStoreId())->get();

return view('livewire.manage-inventory-transfers', [
            'transfers' => $transfers,
            'stores' => $stores,
            'statusBadge' => $this->getStatusBadge(...),
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->editingTransferId = null;
        $this->toStoreId = null;
        $this->transferNotes = '';
        $this->selectedProducts = [];
        $this->searchProduct = '';
        $this->availableProducts = [];
    }

    public function searchProducts()
    {
        if (strlen($this->searchProduct) < 2) {
            $this->availableProducts = [];
            return;
        }

        $this->availableProducts = Product::where('store_id', $this->getStoreId())
            ->where('stock_quantity', '>', 0)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                    ->orWhere('barcode', 'like', '%' . $this->searchProduct . '%');
            })
            ->limit(10)
            ->get();
    }

    public function addProduct($productId)
    {
        $product = Product::find($productId);
        if ($product && !isset($this->selectedProducts[$productId])) {
            $this->selectedProducts[$productId] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'available_stock' => $product->stock_quantity,
                'quantity' => 1,
            ];
        }
        $this->searchProduct = '';
        $this->availableProducts = [];
    }

    public function selectAllProducts()
    {
        $products = Product::where('store_id', $this->getStoreId())
            ->where('stock_quantity', '>', 0)
            ->get();

        foreach ($products as $product) {
            if (!isset($this->selectedProducts[$product->id])) {
                $this->selectedProducts[$product->id] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'available_stock' => $product->stock_quantity,
                    'quantity' => 1,
                ];
            }
        }
        $this->searchProduct = '';
        $this->availableProducts = [];
    }

    public function clearSelectedProducts()
    {
        $this->selectedProducts = [];
    }

    public function removeProduct($productId)
    {
        unset($this->selectedProducts[$productId]);
    }

    #[On('closeModal')]
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    #[On('closeReceiveModal')]
    public function closeReceiveModal()
    {
        $this->showReceiveModal = false;
        $this->receiveItems = [];
    }

    #[On('closeDetailModal')]
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailTransfer = null;
    }

    public function updateQuantity($productId, $quantity)
    {
        $quantity = max(1, (int) $quantity);
        $maxQty = $this->selectedProducts[$productId]['available_stock'] ?? 1;
        
        if ($quantity > $maxQty) {
            $quantity = $maxQty;
        }
        
        if (isset($this->selectedProducts[$productId])) {
            $this->selectedProducts[$productId]['quantity'] = $quantity;
        }
    }

    public function saveTransfer()
    {
        if (!$this->toStoreId) {
            LivewireAlert::title('Error')->text('Please select destination store')->warning()->show();
            return;
        }

        if (empty($this->selectedProducts)) {
            LivewireAlert::title('Error')->text('Please add at least one product')->warning()->show();
            return;
        }

        $hasValidQty = false;
        foreach ($this->selectedProducts as $item) {
            if ($item['quantity'] > 0) {
                $hasValidQty = true;
                break;
            }
        }

        if (!$hasValidQty) {
            LivewireAlert::title('Error')->text('Please specify quantities')->warning()->show();
            return;
        }

        try {
            DB::beginTransaction();

            $transfer = InventoryTransfer::create([
                'store_id' => $this->getStoreId(),
                'from_store_id' => $this->getStoreId(),
                'to_store_id' => $this->toStoreId,
                'requested_by' => Auth::id(),
                'status' => 'pending',
                'notes' => $this->transferNotes,
            ]);

            foreach ($this->selectedProducts as $item) {
                if ($item['quantity'] > 0) {
                    InventoryTransferItem::create([
                        'inventory_transfer_id' => $transfer->id,
                        'product_id' => $item['product_id'],
                        'quantity_requested' => $item['quantity'],
                        'quantity_sent' => 0,
                        'quantity_received' => 0,
                        'item_status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            LivewireAlert::title('Success')->text('Transfer request created')->success()->toast()->show();
            $this->showModal = false;
            $this->resetForm();
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Error')->text('Failed to create transfer: ' . $e->getMessage())->error()->show();
        }
    }

    public function approveTransfer($transferId)
    {
        try {
            $transfer = InventoryTransfer::findOrFail($transferId);
            $transfer->update([
                'approved_by' => Auth::id(),
                'status' => 'approved',
            ]);

            LivewireAlert::title('Success')->text('Transfer approved')->success()->toast()->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')->text('Failed to approve: ' . $e->getMessage())->error()->show();
        }
    }

    public function rejectTransfer($transferId)
    {
        $transfer = InventoryTransfer::findOrFail($transferId);
        $this->dispatch('show-reject-modal', $transferId);
    }

    public function confirmReject($transferId, $reason)
    {
        try {
            $transfer = InventoryTransfer::findOrFail($transferId);
            $transfer->update([
                'approved_by' => Auth::id(),
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            LivewireAlert::title('Success')->text('Transfer rejected')->success()->toast()->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')->text('Failed to reject: ' . $e->getMessage())->error()->show();
        }
    }

    public function sendTransfer($transferId)
    {
        try {
            // Dispatch job for processing (sync for immediate execution)
            ProcessInventoryTransfer::dispatchSync($transferId, 'send');

            LivewireAlert::title('Success')->text('Items sent to destination')->success()->toast()->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')->text('Failed to send: ' . $e->getMessage())->error()->show();
        }
    }

    public function openReceiveModal($transferId)
    {
        $this->receivingTransferId = $transferId;
        $transfer = InventoryTransfer::with('items.product')->findOrFail($transferId);
        
        $this->receiveItems = [];
        foreach ($transfer->items as $item) {
            $this->receiveItems[$item->id] = [
                'item_id' => $item->id,
                'product_name' => $item->product->name,
                'sku' => $item->product->sku,
                'sent_quantity' => $item->quantity_sent,
                'received_quantity' => $item->quantity_sent, // Default to full
            ];
        }
        
        $this->showReceiveModal = true;
    }

    public function receiveTransfer()
    {
        try {
            $receivedQuantities = [];
            foreach ($this->receiveItems as $itemId => $data) {
                $receivedQuantities[$itemId] = (int) $data['received_quantity'];
            }

            // Dispatch job for processing (sync for immediate execution)
            ProcessInventoryTransfer::dispatchSync($this->receivingTransferId, 'receive', $receivedQuantities);

            LivewireAlert::title('Success')->text('Transfer received and inventory updated')->success()->toast()->show();
            $this->showReceiveModal = false;
            $this->receiveItems = [];
        } catch (\Exception $e) {
            LivewireAlert::title('Error')->text('Failed to receive: ' . $e->getMessage())->error()->show();
        }
    }

    public function viewDetail($transferId)
    {
        $this->detailTransfer = InventoryTransfer::with(['fromStore', 'toStore', 'requestedBy', 'approvedBy', 'receivedBy', 'items.product'])
            ->findOrFail($transferId);
        $this->showDetailModal = true;
    }

    public function cancelTransfer($transferId)
    {
        try {
            $transfer = InventoryTransfer::findOrFail($transferId);
            if ($transfer->status === 'pending') {
                $transfer->update([
                    'status' => 'cancelled',
                ]);
                LivewireAlert::title('Success')->text('Transfer cancelled')->success()->toast()->show();
            } else {
                LivewireAlert::title('Error')->text('Cannot cancel transfer in current status')->warning()->show();
            }
        } catch (\Exception $e) {
            LivewireAlert::title('Error')->text('Failed to cancel: ' . $e->getMessage())->error()->show();
        }
    }

    public function getStatusBadge($status)
    {
        return match($status) {
            'pending' => 'warning',
            'approved' => 'info',
            'in_transit' => 'primary',
            'received' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            'sent' => 'primary',
            default => 'secondary',
        };
    }
}
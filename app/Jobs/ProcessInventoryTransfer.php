<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferItem;
use Illuminate\Support\Facades\Log;

class ProcessInventoryTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $transferId;
    public string $action; // 'send' or 'receive'
    public array $receivedQuantities; // For receive action

    public function __construct(int $transferId, string $action, array $receivedQuantities = [])
    {
        $this->transferId = $transferId;
        $this->action = $action;
        $this->receivedQuantities = $receivedQuantities;
    }

    public function handle(): void
    {
        $transfer = InventoryTransfer::with('items', 'fromStore', 'toStore')->find($this->transferId);

        if (!$transfer) {
            Log::error("Transfer not found: {$this->transferId}");
            return;
        }

        if ($this->action === 'send') {
            $this->processSend($transfer);
        } elseif ($this->action === 'receive') {
            $this->processReceive($transfer);
        }
    }

    private function processSend(InventoryTransfer $transfer): void
    {
        foreach ($transfer->items as $item) {
            $product = Product::find($item->product_id);
            
            if ($product && $product->stock_quantity >= $item->quantity_requested) {
                $product->decrement('stock_quantity', $item->quantity_requested);
                
                $item->update([
                    'quantity_sent' => $item->quantity_requested,
                    'item_status' => 'sent',
                ]);
            }
        }

        $transfer->update(['status' => 'in_transit']);
        
        Log::info("Transfer {$transfer->id} sent to {$transfer->toStore->name}");
    }

    private function processReceive(InventoryTransfer $transfer): void
    {
        foreach ($transfer->items as $item) {
            $receivedQty = $this->receivedQuantities[$item->id] ?? $item->quantity_sent;
            
            if ($receivedQty > 0) {
                // Check if product with same barcode exists in destination store
                $sourceProduct = Product::find($item->product_id);
                
                $destProduct = null;
                if ($sourceProduct) {
                    // Check if same barcode exists in destination store
                    $destProduct = Product::where('store_id', $transfer->to_store_id)
                        ->where('barcode', $sourceProduct->barcode)
                        ->first();
                    
                    // If not in destination, check if barcode exists anywhere (global uniqueness issue)
                    if (!$destProduct && $sourceProduct->barcode) {
                        $existingBarcode = Product::where('barcode', $sourceProduct->barcode)->exists();
                        if ($existingBarcode) {
                            // Generate completely new unique barcode
                            $sourceProduct->barcode = 'TRF-' . uniqid();
                            $sourceProduct->sku = 'TRF-' . substr(($sourceProduct->sku ?? uniqid()), -10) . '-' . rand(100, 999);
                        }
                    }
                }

                if ($destProduct) {
                    // Update existing product stock
                    $destProduct->increment('stock_quantity', $receivedQty);
                } elseif ($sourceProduct) {
                    // Create new product in destination store
                    $newProduct = $sourceProduct->replicate();
                    $newProduct->store_id = $transfer->to_store_id;
                    $newProduct->stock_quantity = $receivedQty;
                    $newProduct->save();
                    
                    Log::info("Created new product {$newProduct->id} in store {$transfer->to_store_id}");
                }

                $item->update([
                    'quantity_received' => $receivedQty,
                    'item_status' => $receivedQty < $item->quantity_sent ? 'rejected' : 'received',
                ]);
            }
        }

        $transfer->update(['status' => 'received']);
        
        Log::info("Transfer {$transfer->id} received at {$transfer->toStore->name}");
    }
}
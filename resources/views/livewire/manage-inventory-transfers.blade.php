<div>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Inventory Transfers</h2>
            <button wire:click="openCreateModal" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Transfer Request
            </button>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'pending' ? 'active' : '' }}" wire:click="$set('tab', 'pending')">Pending</button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'in_transit' ? 'active' : '' }}" wire:click="$set('tab', 'in_transit')">In Transit</button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'received' ? 'active' : '' }}" wire:click="$set('tab', 'received')">Received</button>
            </li>
            <li class="nav-item">
                <button class="nav-link {{ $tab === 'all' ? 'active' : '' }}" wire:click="$set('tab', 'all')">All</button>
            </li>
        </ul>

        <!-- Transfers Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td>#{{ $transfer->id }}</td>
                                <td>{{ $transfer->fromStore->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->toStore->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->items->count() }} items</td>
                                <td><span class="badge bg-{{ $statusBadge($transfer->status) }}">{{ ucfirst($transfer->status) }}</span></td>
                                <td>{{ $transfer->requestedBy->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button wire:click="viewDetail({{ $transfer->id }})" class="btn btn-sm btn-info">View</button>
                                    @if($transfer->status === 'pending')
                                        <button wire:click="approveTransfer({{ $transfer->id }})" class="btn btn-sm btn-success">Approve</button>
                                        <button wire:click="rejectTransfer({{ $transfer->id }})" class="btn btn-sm btn-danger">Reject</button>
                                    @endif
                                    @if($transfer->status === 'approved')
                                        <button wire:click="sendTransfer({{ $transfer->id }})" class="btn btn-sm btn-primary">Send</button>
                                    @endif
                                    @if($transfer->status === 'in_transit')
                                        <button wire:click="openReceiveModal({{ $transfer->id }})" class="btn btn-sm btn-success">Receive</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No transfers found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $transfers->links() }}</div>
        </div>
    </div>

    <!-- Create Transfer Modal -->
    <x-modal-wrapper id="createTransfer" title="New Transfer Request" :show="$showModal" bgColor="bg-primary" icon="fas fa-exchange-alt" closeEvent="closeModal">
        <div class="mb-3">
            <label class="form-label">Destination Store *</label>
            <select wire:model="toStoreId" class="form-select">
                <option value="">Select store...</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-label mb-0">Add Products</label>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" wire:click="selectAllProducts">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearSelectedProducts">Clear All</button>
                </div>
            </div>
            <input type="text" wire:model="searchProduct" wire:keyup="searchProducts" class="form-control mt-2" placeholder="Search by name, SKU, or barcode...">
            @if(count($availableProducts) > 0)
                <div class="list-group mt-1" style="max-height: 200px; overflow-y: auto;">
                    @foreach($availableProducts as $product)
                        <button type="button" class="list-group-item list-group-item-action" wire:click="addProduct({{ $product->id }})">
                            {{ $product->name }} (SKU: {{ $product->sku }}) - Stock: {{ $product->stock_quantity }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if(count($selectedProducts) > 0)
            <div class="mb-3">
                <label class="form-label">Selected Products ({{ count($selectedProducts) }})</label>
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Product</th><th>Available</th><th style="width: 100px;">Qty</th><th style="width: 40px;"></th></tr>
                        </thead>
                        <tbody>
                            @foreach($selectedProducts as $productId => $item)
                                <tr>
                                    <td><strong>{{ $item['product_name'] }}</strong><br><small class="text-muted">SKU: {{ $item['sku'] }}</small></td>
                                    <td>{{ $item['available_stock'] }}</td>
                                    <td><input type="number" min="1" max="{{ $item['available_stock'] }}" value="{{ $item['quantity'] }}" wire:change="updateQuantity({{ $productId }}, $event.target.value)" class="form-control form-control-sm"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger" wire:click="removeProduct({{ $productId }})"><i class="fas fa-times"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="alert alert-info">No products selected. Search and add products above.</div>
        @endif

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea wire:model="transferNotes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
        </div>

        <x-slot:footer>
            <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
            <button type="button" class="btn btn-primary" wire:click="saveTransfer" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveTransfer">Create Transfer</span>
                <span wire:loading wire:target="saveTransfer"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </x-slot:footer>
    </x-modal-wrapper>

    <!-- Receive Modal -->
    <x-modal-wrapper id="receiveModal" title="Receive Transfer" :show="$showReceiveModal" bgColor="bg-success" icon="fas fa-check-circle" closeEvent="closeReceiveModal">
        <p class="text-muted">Verify quantities received. Adjust if items are missing or damaged.</p>
        @foreach($receiveItems as $itemId => $item)
            <div class="d-flex align-items-center mb-3 p-2 border rounded">
                <div class="flex-grow-1">
                    <strong>{{ $item['product_name'] }}</strong>
                    <small class="text-muted d-block">SKU: {{ $item['sku'] }}</small>
                </div>
                <div class="text-center mx-3">
                    <small class="text-muted d-block">Sent</small>
                    <strong>{{ $item['sent_quantity'] }}</strong>
                </div>
                <div>
                    <label class="small">Received</label>
                    <input type="number" min="0" max="{{ $item['sent_quantity'] }}" wire:model="receiveItems.{{ $itemId }}.received_quantity" class="form-control form-control-sm" style="width: 80px;">
                </div>
            </div>
        @endforeach

        <x-slot:footer>
            <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeReceiveModal')">Cancel</button>
            <button type="button" class="btn btn-success" wire:click="receiveTransfer" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="receiveTransfer">Confirm Receipt</span>
                <span wire:loading wire:target="receiveTransfer"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
            </button>
        </x-slot:footer>
    </x-modal-wrapper>

    <!-- Detail Modal -->
    @if($showDetailModal && $detailTransfer)
        <x-modal-wrapper id="detailModal" title="Transfer #{{ $detailTransfer->id }} Details" :show="$showDetailModal" bgColor="bg-info" icon="fas fa-info-circle" closeEvent="closeDetailModal">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>From:</strong> {{ $detailTransfer->fromStore->name }}</p>
                    <p><strong>To:</strong> {{ $detailTransfer->toStore->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge bg-{{ $statusBadge($detailTransfer->status) }}">{{ ucfirst($detailTransfer->status) }}</span></p>
                    <p><strong>Requested By:</strong> {{ $detailTransfer->requestedBy->name ?? 'N/A' }}</p>
                </div>
            </div>

            @if($detailTransfer->notes)<p><strong>Notes:</strong> {{ $detailTransfer->notes }}</p>@endif
            @if($detailTransfer->rejection_reason)<p class="text-danger"><strong>Rejection Reason:</strong> {{ $detailTransfer->rejection_reason }}</p>@endif

            <h6>Items</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>Product</th><th>SKU</th><th>Requested</th><th>Sent</th><th>Received</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($detailTransfer->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->sku }}</td>
                                <td>{{ $item->quantity_requested }}</td>
                                <td>{{ $item->quantity_sent }}</td>
                                <td>{{ $item->quantity_received }}</td>
                                <td><span class="badge bg-{{ $statusBadge($item->item_status) }}">{{ ucfirst($item->item_status) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-slot:footer>
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeDetailModal')">Close</button>
            </x-slot:footer>
        </x-modal-wrapper>
    @endif
</div>
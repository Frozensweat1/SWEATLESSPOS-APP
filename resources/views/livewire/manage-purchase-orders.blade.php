<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ $componentName }} | {{ $pageTitle }}</h2>
        </div>

        <div class="card mb-4">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search order number..."
                        wire:model.live="search">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="sent">Sent</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="supplierFilter">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="startDate">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="endDate">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Order #</th>
                                <th class="text-nowrap">Supplier</th>
                                <th class="text-nowrap">Items</th>
                                <th class="text-nowrap">Total</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Order Date</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="text-nowrap">{{ $order->order_number }}</td>
                                    <td class="text-nowrap">{{ $order->supplier?->name ?? 'N/A' }}</td>
                                    <td class="text-nowrap">{{ $order->items_count ?? 0 }}</td>
                                    <td class="text-nowrap">Ghs {{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-nowrap">
                                        <span class="badge bg-{{ $order->status === 'received' ? 'success' : ($order->status === 'sent' ? 'info' : ($order->status === 'cancelled' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $order->order_date?->format('Y-m-d') ?? 'N/A' }}
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="dropdown font-sans-serif position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                type="button" data-bs-toggle="dropdown" data-boundary="window"
                                                aria-haspopup="true" aria-expanded="false">
                                                <span class="fas fa-ellipsis-h fs--1"></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end border py-0">
                                                <div class="bg-white py-2">
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click="viewOrder({{ $order->id }})">View</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click="updateStatus({{ $order->id }}, 'sent')">Mark
                                                        Sent</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        wire:click="updateStatus({{ $order->id }}, 'received')">Mark
                                                        Received</a>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        wire:click="updateStatus({{ $order->id }}, 'cancelled')">Cancel</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-info text-center mb-0">No purchase orders found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    @if ($showViewModal && $selectedOrder)
        <div class="modal fade show" id="viewOrderModal" data-bs-keyboard="false" data-bs-backdrop="static" style="display: block;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Purchase Order
                            {{ $selectedOrder->order_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeViewModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div><strong>Supplier:</strong> {{ $selectedOrder->supplier?->name ?? 'N/A' }}</div>
                            <div><strong>Status:</strong> {{ ucfirst($selectedOrder->status) }}</div>
                            <div><strong>Order Date:</strong>
                                {{ $selectedOrder->order_date?->format('Y-m-d') ?? 'N/A' }}</div>
                            @if ($selectedOrder->expected_date)
                                <div><strong>Expected Date:</strong>
                                    {{ $selectedOrder->expected_date->format('Y-m-d') }}</div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                                <thead class="bg-200 text-900">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-nowrap">Qty</th>
                                        <th class="text-nowrap">Unit Cost</th>
                                        <th class="text-nowrap">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($selectedOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->product?->name ?? 'N/A' }}</td>
                                            <td class="text-nowrap">{{ $item->quantity }}</td>
                                            <td class="text-nowrap">Ghs {{ number_format($item->unit_cost, 2) }}</td>
                                            <td class="text-nowrap">Ghs {{ number_format($item->line_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-nowrap">Ghs {{ number_format($selectedOrder->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" wire:click="closeViewModal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

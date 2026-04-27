<div>
    <div class="container py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Sales Report</h4>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-3">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select id="paymentMethod" class="form-select" wire:model.live="paymentMethod">
                            <option value="">All</option>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchTerm" class="form-label">Search</label>
                        <input type="text" id="searchTerm" class="form-control" placeholder="Customer, Product, Sale ID..."
                            wire:model.live="searchTerm">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total Revenue</h6>
                                <h4 class="mb-0">Ghs {{ number_format($summary['totalRevenue'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Items Sold</h6>
                                <h4 class="mb-0">{{ number_format($summary['totalItems'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h6 class="card-title">Transactions</h6>
                                <h4 class="mb-0">{{ number_format($summary['transactionCount'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Avg Order Value</h6>
                                <h4 class="mb-0">Ghs {{ number_format($summary['averageOrder'] ?? 0, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap" scope="col">Sale ID</th>
                                <th class="text-nowrap" scope="col">Customer</th>
                                <th class="text-nowrap" scope="col">Items</th>
                                <th class="text-nowrap" scope="col">Total</th>
                                <th class="text-nowrap" scope="col">Payment</th>
                                <th class="text-nowrap" scope="col">Date</th>
                                <th class="text-nowrap" scope="col">Cashier</th>
                                <th class="text-nowrap text-end" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="text-nowrap">#{{ $sale->id }}</td>
                                    <td class="text-nowrap">{{ $sale->customer?->name ?? 'Guest' }}</td>
                                    <td class="text-nowrap">{{ $sale->saleDetails->sum('quantity') }}</td>
                                    <td class="text-nowrap">Ghs {{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="text-nowrap">{{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}</td>
                                    <td class="text-nowrap">{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td class="text-nowrap">{{ $sale->user->name ?? 'N/A' }}</td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-falcon-primary btn-sm" type="button"
                                            wire:click="viewSaleDetails({{ $sale->id }})">
                                            <span class="fas fa-eye me-1"></span>View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No sales found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{ $sales->links() }}
                    <div class="d-flex gap-2">
                        <button class="btn btn-falcon-primary btn-sm" type="button" wire:click="export('csv')">
                            <span class="fas fa-file-csv me-1"></span> CSV
                        </button>
                        <button class="btn btn-falcon-success btn-sm" type="button" wire:click="export('xlsx')">
                            <span class="fas fa-file-excel me-1"></span> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($showSaleDetails && $selectedSale)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Sale Details #{{ $selectedSale->id }}</h5>
                        <button type="button" class="btn-close" wire:click="closeSaleDetails"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3 g-3">
                            <div class="col-md-6">
                                <strong>Customer:</strong> {{ $selectedSale->customer?->name ?? 'Guest' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Cashier:</strong> {{ $selectedSale->user?->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Payment Method:</strong>
                                {{ ucwords(str_replace('_', ' ', $selectedSale->payment_method)) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Date:</strong> {{ $selectedSale->created_at->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($selectedSale->saleDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->product?->name ?? 'N/A' }}</td>
                                            <td class="text-end">{{ $detail->quantity }}</td>
                                            <td class="text-end">Ghs {{ number_format($detail->price_per_unit, 2) }}</td>
                                            <td class="text-end">Ghs {{ number_format($detail->subtotal, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No sale items found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="me-auto fw-bold">
                            Total: Ghs {{ number_format($selectedSale->total_amount, 2) }}
                        </div>
                        <button type="button" class="btn btn-secondary" wire:click="closeSaleDetails">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

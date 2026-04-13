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
                            <option value="mobile_money">Mobile Payment</option>
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
                                <th class="text-nowrap" scope="col">Product</th>
                                <th class="text-nowrap" scope="col">Qty</th>
                                <th class="text-nowrap" scope="col">Price</th>
                                <th class="text-nowrap" scope="col">Total</th>
                                <th class="text-nowrap" scope="col">Payment</th>
                                <th class="text-nowrap" scope="col">Date</th>
                                <th class="text-nowrap" scope="col">Cashier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                @foreach ($sale->saleDetails as $detail)
                                    <tr>
                                        <td class="text-nowrap">{{ $sale->id }}</td>
                                        <td class="text-nowrap">{{ $sale->customer?->name ?? 'Guest' }}</td>
                                        <td class="text-nowrap">{{ $detail->product->name ?? 'N/A' }}</td>
                                        <td class="text-nowrap">{{ $detail->quantity }}</td>
                                        <td class="text-nowrap">Ghs {{ number_format($detail->price_per_unit, 2) }}</td>
                                        <td class="text-nowrap">Ghs {{ number_format($detail->subtotal, 2) }}</td>
                                        <td class="text-nowrap">{{ ucfirst($sale->payment_method) }}</td>
                                        <td class="text-nowrap">{{ $sale->created_at->format('Y-m-d') }}</td>
                                        <td class="text-nowrap">{{ $sale->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr><td colspan="9" class="text-center">No sales found</td></tr>
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
</div>
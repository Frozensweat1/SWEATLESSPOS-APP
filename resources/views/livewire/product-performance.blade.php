<div>
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sort By</label>
                        <select class="form-select" wire:model.live="sortBy">
                            <option value="revenue">Revenue</option>
                            <option value="profit">Profit</option>
                            <option value="quantity">Quantity Sold</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-falcon-success btn-sm" wire:click="export">
                            <span class="fas fa-file-excel me-1"></span> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h4 class="mb-0">Ghs {{ number_format($stats['totalRevenue'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Items Sold</h6>
                        <h4 class="mb-0">{{ number_format($stats['totalQuantity'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Total Profit</h6>
                        <h4 class="mb-0">Ghs {{ number_format($stats['totalProfit'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Top Product</h6>
                        <h5 class="mb-0">{{ $topSellers->first()?->product->name ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">Top Sellers</h6>
                    </div>
                    <div class="card-body">
                        @forelse($topSellers as $index => $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $index + 1 }}. {{ $item->product->name }}</span>
                                <span class="badge bg-success">{{ $item->quantitySold }} sold</span>
                            </div>
                        @empty
                            <p class="text-muted">No sales data</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">Slow Movers (Low Sales)</h6>
                    </div>
                    <div class="card-body">
                        @forelse($slowMovers as $index => $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $item->product->name }}</span>
                                <span class="badge bg-warning">{{ $item->quantitySold }} sold</span>
                            </div>
                        @empty
                            <p class="text-muted">No slow moving items</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Product Performance Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Qty Sold</th>
                                <th>Revenue</th>
                                <th>Cost</th>
                                <th>Profit</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>{{ $item->quantitySold }}</td>
                                    <td>Ghs {{ number_format($item->revenue, 2) }}</td>
                                    <td>Ghs {{ number_format($item->cost, 2) }}</td>
                                    <td class="{{ $item->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        Ghs {{ number_format($item->profit, 2) }}
                                    </td>
                                    <td>{{ $item->product->stock_quantity }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No products found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
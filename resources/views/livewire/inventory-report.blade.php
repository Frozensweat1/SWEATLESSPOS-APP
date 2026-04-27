<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Inventory Report</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-falcon-primary btn-sm" wire:click="export('csv')">
                    <span class="fas fa-file-csv me-1"></span> CSV
                </button>
                <button class="btn btn-falcon-success btn-sm" wire:click="export('xlsx')">
                    <span class="fas fa-file-excel me-1"></span> Excel
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body py-2">
                            <small>Total Products</small>
                            <h5 class="mb-0">{{ number_format($stats['totalProducts']) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <small>Total Stock</small>
                            <h5 class="mb-0">{{ number_format($stats['totalStock']) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body py-2">
                            <small>Stock Value</small>
                            <h5 class="mb-0">Ghs {{ number_format($stats['totalValue'], 0) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body py-2">
                            <small>Low Stock</small>
                            <h5 class="mb-0">{{ number_format($stats['lowStock']) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <small>Out of Stock</small>
                            <h5 class="mb-0">{{ number_format($stats['outOfStock']) }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body py-2">
                            <small>Expiring Soon</small>
                            <h5 class="mb-0">{{ number_format($stats['expiringSoon']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" wire:model.live="search" class="form-control"
                        placeholder="Search by name, SKU, barcode...">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="supplierFilter" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" wire:model.live="lowStock" class="form-check-input" id="lowStock">
                        <label class="form-check-label" for="lowStock">Low Stock</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" wire:model.live="expiringSoon" class="form-check-input"
                            id="expiringSoon">
                        <label class="form-check-label" for="expiringSoon">Expiring Soon</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <input type="number" wire:model.live="lowStockThreshold" class="form-control form-control-sm"
                        placeholder="Threshold" title="Low stock threshold">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Cost</th>
                            <th>Stock</th>
                            <th>Value</th>
                            <th>Expiry</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>Ghs {{ number_format($product->price, 2) }}</td>
                                <td>Ghs {{ number_format($product->cost_price, 2) }}</td>
                                <td class="{{ $product->stock_quantity <= $lowStockThreshold ? 'text-warning' : '' }}">
                                    {{ $product->stock_quantity }}</td>
                                <td>Ghs {{ number_format($product->stock_quantity * $product->price, 2) }}</td>
                                <td
                                    class="{{ $product->expiry_date && $product->expiry_date->diffInDays(now()) <= 30 ? 'text-danger' : '' }}">
                                    {{ $product->expiry_date?->format('Y-m-d') ?? 'N/A' }}
                                </td>
                                <td>
                                    @if ($product->stock_quantity <= 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->stock_quantity <= $lowStockThreshold)
                                        <span class="badge bg-warning">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No products found</td>
                            </tr>
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

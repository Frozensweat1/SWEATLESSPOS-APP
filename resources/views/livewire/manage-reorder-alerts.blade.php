<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ $componentName }} | {{ $pageTitle }}</h2>
            <div class="text-muted small">
                Threshold: {{ $lowStockThreshold }}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search products..."
                        wire:model.live="search">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="supplierFilter">
                        <option value="">All Suppliers</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Product</th>
                                <th class="text-nowrap">SKU</th>
                                <th class="text-nowrap">Supplier</th>
                                <th class="text-nowrap">Stock</th>
                                <th class="text-nowrap">Threshold</th>
                                <th class="text-nowrap">Reorder Qty</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $shownSuppliers = [];
                            @endphp
                            @forelse ($products as $product)
                                @php
                                    $supplierId = $product->supplier_id;
                                    $reorderQty = max(0, $lowStockThreshold - (int) $product->stock_quantity);
                                @endphp
                                <tr>
                                    <td class="text-nowrap">{{ $product->name }}</td>
                                    <td class="text-nowrap">{{ $product->sku }}</td>
                                    <td class="text-nowrap">{{ $product->supplier?->name ?? 'N/A' }}</td>
                                    <td class="text-nowrap">{{ $product->stock_quantity }}</td>
                                    <td class="text-nowrap">{{ $lowStockThreshold }}</td>
                                    <td class="text-nowrap">{{ $reorderQty }}</td>
                                    <td class="text-nowrap">
                                        @if ($supplierId && !in_array($supplierId, $shownSuppliers, true))
                                            <button class="btn btn-falcon-primary btn-sm d-flex align-items-center"
                                                type="button" wire:click="createPurchaseOrder({{ $supplierId }})"
                                                wire:loading.attr="disabled"
                                                wire:target="createPurchaseOrder({{ $supplierId }})">
                                                <span class="fas fa-file-invoice-dollar"
                                                    data-fa-transform="shrink-3 down-2"></span>
                                                <span class="ms-1">Create PO</span>
                                                <span wire:loading
                                                    wire:target="createPurchaseOrder({{ $supplierId }})"
                                                    class="spinner-border spinner-border-sm ms-2" role="status"
                                                    aria-hidden="true"></span>
                                            </button>
                                            @php
                                                $shownSuppliers[] = $supplierId;
                                            @endphp
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-info text-center mb-0">No low stock items found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

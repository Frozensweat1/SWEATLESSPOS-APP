<div>
    {{-- Success is as dangerous as failure. --}}

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Inventory Report</h4>
            <div class="btn-group">
                <!-- Export Button -->
                <button class="btn btn-falcon-primary btn-sm d-flex" type="button" wire:click="export('csv')">
                    <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('csv')">ing...</span> To CSV</span>
                    <span wire:loading wire:target="export('csv')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="export('xlsx')">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('xlsx')">ing...</span> Excel</span>
                    <span wire:loading wire:target="export('xlsx')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control"
                        placeholder="Search products...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped table-responsive fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th scope="col">Cost Price</th>
                            <th class="text-nowrap" scope="col">Stock Quantity</th>
                            <th class="text-nowrap" scope="col">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="text-nowrap">{{ $product->name }}</td>
                                <td class="text-nowrap">{{ $product->sku }}</td>
                                <td class="text-nowrap">{{ $product->barcode }}</td>
                                <td class="text-nowrap">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="text-nowrap">{{ $product->supplier->name ?? 'N/A' }}</td>
                                <td class="text-nowrap">Ghs {{ $product->price }}</td>
                                <td class="text-nowrap">Ghs {{ $product->cost_price }}</td>
                                <td class="text-nowrap">{{ $product->stock_quantity }}</td>
                                <td class="text-nowrap">
                                    {{ $product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No products found.</td>
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

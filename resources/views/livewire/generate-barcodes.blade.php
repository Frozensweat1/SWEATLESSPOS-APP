<div class="px-2 py-3 container-fluid px-sm-3 px-md-4">
    <!-- Alert Display -->
    @if (session()->has('alert'))
        @php $alert = session('alert') @endphp
        <div class="mb-4 alert alert-{{ $alert['type'] === 'error' ? 'danger' : ($alert['type'] === 'success' ? 'success' : ($alert['type'] === 'warning' ? 'warning' : 'info')) }} alert-dismissible fade show" role="alert">
            <strong>{{ $alert['title'] ?? 'Alert' }}</strong>
            <div>{{ $alert['message'] ?? '' }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-0">{{ $pageTitle }} > {{ $componentName }}</h1>
                    <p class="mt-1 text-muted small">Generate and export product barcodes as PDF</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-4 card">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Search Products</label>
                    <input
                        type="text"
                        wire:model.live="searchQuery"
                        placeholder="Search by product name or barcode..."
                        class="form-control"
                    />
                </div>
                <div class="col-md-4">
                    <button
                        wire:click="clearSearch"
                        class="btn btn-secondary w-100"
                    >
                        <i class="fas fa-times me-2"></i>Clear Search
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Section -->
    <div class="mb-4 card">
        <div class="card-body">
            <h5 class="card-title">Barcode Settings</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Barcode Type</label>
                    <select wire:model="barcodeType" class="form-control">
                        <option value="CODE128">CODE128 (Recommended)</option>
                        <option value="CODE39">CODE39</option>
                        <option value="EAN13">EAN13</option>
                        <option value="UPCA">UPCA</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Items Per Page</label>
                    <input
                        type="number"
                        wire:model="itemsPerPage"
                        min="1"
                        max="50"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Selection Summary -->
    <div class="mb-4 card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Selected: <span class="badge bg-primary">{{ count(array_filter($selectedProducts)) }}</span></h5>
                <p class="mt-2 text-muted small">Select products below to generate barcodes</p>
            </div>
            <div class="btn-group" role="group">
                <button
                    wire:click="generateBarcodes"
                    wire:loading.attr="disabled"
                    class="btn btn-success"
                    @if ($isGenerating) disabled @endif
                >
                    @if ($isGenerating)
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Generating...
                    @else
                        <i class="fas fa-file-pdf me-2"></i>Generate Selected
                    @endif
                </button>
                <button
                    wire:click="generateAllBarcodes"
                    wire:loading.attr="disabled"
                    class="btn btn-info"
                    @if ($isGenerating) disabled @endif
                >
                    <i class="fas fa-print me-2"></i>Generate All
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">
                            <input
                                type="checkbox"
                                wire:model.live="selectAll"
                                class="form-check-input"
                            />
                        </th>
                        <th>Product Name</th>
                        <th>Barcode</th>
                        <th>SKU</th>
                        <th style="width: 80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
<td>
    <input
        type="checkbox"
        wire:model="selectedProducts.{{ $product->id }}"
        class="form-check-input"
    />
</td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $product->barcode }}</code>
                            </td>
                            <td>
                                {{ $product->sku ?? 'N/A' }}
                            </td>
<td>
    <button
        wire:click="selectProductToggle({{ $product->id }})"
        class="btn btn-sm @if (array_key_exists($product->id, $selectedProducts)) btn-primary @else btn-outline-primary @endif"
    >
        @if (array_key_exists($product->id, $selectedProducts))
            <i class="fas fa-check"></i>
        @else
            <i class="fas fa-square"></i>
        @endif
    </button>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                No products with barcodes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($products->count())
            <div class="card-footer bg-light">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-4 alert alert-info">
        <h6 class="alert-heading">
            <i class="fas fa-info-circle me-2"></i>How It Works
        </h6>
        <ul class="mb-0 small">
            <li>Select one or more products from the list above</li>
            <li>Choose your preferred barcode format (CODE128 is recommended)</li>
            <li>Click "Generate Selected" or "Generate All" to start the process</li>
            <li>Your PDF will be generated in the background and become available for download</li>
            <li>All products must have a barcode field populated to be included</li>
        </ul>
    </div>

    <!-- Download History Section -->
    <div class="mt-4">
        <button
            wire:click="toggleDownloadHistory"
            class="btn btn-outline-secondary"
        >
            <i class="fas fa-history me-2"></i>
            @if ($showDownloadHistory)
                Hide Download History
            @else
                Show Download History
            @endif
        </button>

        @if ($showDownloadHistory)
            <div class="mt-3 card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Recently Generated Barcodes</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-2"></i>
                        Generated PDF files are stored securely. Use the links below to download or manage your files.
                    </p>
                    <div class="alert alert-warning small" role="alert">
                        <strong>Note:</strong> Files are stored in the application storage directory.
                        Implement a periodic cleanup task to remove old files.
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

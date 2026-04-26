<div class="px-2 py-3 container-fluid px-sm-3 px-md-4">
    <!-- Alert Display -->
    @if (session()->has('alert'))
        @php $alert = session('alert') @endphp
        <div class="mb-4 alert alert-{{ $alert['type'] === 'error' ? 'danger' : ($alert['type'] === 'success' ? 'success' : ($alert['type'] === 'warning' ? 'warning' : 'info')) }} alert-dismissible fade show"
            role="alert">
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
                <div class="col-md-6">
                    <label class="form-label">Search Products</label>
                    <input type="text" wire:model.live="searchQuery"
                        placeholder="Search by product name or barcode..." class="form-control" />
                </div>
                <div class="col-md-3">
                    <button wire:click="clearSearch" wire:loading.attr="disabled" wire:target="clearSearch"
                        class="btn btn-secondary w-100">
                        <span wire:loading.remove wire:target="clearSearch">
                            <i class="fas fa-times me-2"></i>Clear Search
                        </span>
                        <span wire:loading wire:target="clearSearch">
                            <span class="spinner-border spinner-border-sm me-2" role="status"
                                aria-hidden="true"></span>
                            Clearing...
                        </span>
                    </button>
                </div>
                <div class="col-md-3">
                    <button wire:click="regenerateBarcodesFromStoreAndProductIds" wire:loading.attr="disabled"
                        wire:target="regenerateBarcodesFromStoreAndProductIds" class="btn btn-warning w-100">
                        <span wire:loading.remove wire:target="regenerateBarcodesFromStoreAndProductIds">
                            <i class="fas fa-barcode me-2"></i>Regenerate All Barcodes
                        </span>
                        <span wire:loading wire:target="regenerateBarcodesFromStoreAndProductIds">
                            <span class="spinner-border spinner-border-sm me-2" role="status"
                                aria-hidden="true"></span>
                            Updating...
                        </span>
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
                    <input type="number" wire:model="itemsPerPage" min="1" max="50"
                        class="form-control" />
                </div>
            </div>
        </div>
    </div>

    <!-- Selection Summary -->
    <div class="mb-4 card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Selected: <span class="badge bg-primary">{{ $this->selectedCount }}</span></h5>
                <p class="mt-2 text-muted small">Select products below to generate barcodes</p>
            </div>
            <div class="btn-group" role="group">
                <button wire:click="generateBarcodes" wire:loading.attr="disabled" wire:target="generateBarcodes"
                    class="btn btn-success" @disabled($this->selectedCount === 0)>
                    <span wire:loading.remove wire:target="generateBarcodes">
                        <i class="fas fa-file-pdf me-2"></i>Generate Selected
                    </span>
                    <span wire:loading wire:target="generateBarcodes">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Queueing Selected...
                    </span>
                </button>
                <button wire:click="generateAllBarcodes" wire:loading.attr="disabled" wire:target="generateAllBarcodes"
                    class="btn btn-info">
                    <span wire:loading.remove wire:target="generateAllBarcodes">
                        <i class="fas fa-print me-2"></i>Generate All
                    </span>
                    <span wire:loading wire:target="generateAllBarcodes">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Queueing All...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" wire:model.live="selectAll" class="form-check-input" />
                        </th>
                        <th>Product Name</th>
                        <th>Barcode</th>
                        <th>Preview</th>
                        <th>SKU</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <input type="checkbox" wire:model="selectedProducts.{{ $product->id }}"
                                    class="form-check-input" />
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                            </td>
                            <td>
                                <code>{{ $product->barcode }}</code>
                            </td>
                            <td>
                                @php
                                    $barcodeImage = $this->barcodePreview((string) $product->barcode);
                                @endphp
                                @if ($barcodeImage)
                                    <img src="{{ $barcodeImage }}" alt="Barcode {{ $product->barcode }}"
                                        style="height: 42px; width: auto;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                {{ $product->sku ?? 'N/A' }}
                            </td>
                            <td>
                                <div class="gap-1 d-flex align-items-center">
                                    <button wire:click="selectProductToggle({{ $product->id }})"
                                        class="btn btn-sm @if (!empty($selectedProducts[$product->id])) btn-primary @else btn-outline-primary @endif"
                                        title="Toggle Select">
                                        @if (!empty($selectedProducts[$product->id]))
                                            <i class="fas fa-check"></i>
                                        @else
                                            <i class="fas fa-square"></i>
                                        @endif
                                    </button>
                                    <button wire:click="generateSingleBarcode({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="generateSingleBarcode({{ $product->id }})"
                                        class="btn btn-sm btn-outline-success" title="Generate This Product">
                                        <span wire:loading.remove
                                            wire:target="generateSingleBarcode({{ $product->id }})">
                                            <i class="fas fa-file-pdf me-1"></i>Generate
                                        </span>
                                        <span wire:loading wire:target="generateSingleBarcode({{ $product->id }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                    <button wire:click="updateSingleProductBarcode({{ $product->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="updateSingleProductBarcode({{ $product->id }})"
                                        class="btn btn-sm btn-outline-warning" title="Update This Product Barcode">
                                        <span wire:loading.remove
                                            wire:target="updateSingleProductBarcode({{ $product->id }})">
                                            <i class="fas fa-sync-alt me-1"></i>Update Barcode
                                        </span>
                                        <span wire:loading
                                            wire:target="updateSingleProductBarcode({{ $product->id }})">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-muted">
                                <i class="mb-3 fas fa-inbox fa-2x d-block"></i>
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
        <button wire:click="toggleDownloadHistory" class="btn btn-outline-secondary">
            <i class="fas fa-history me-2"></i>
            @if ($showDownloadHistory)
                Hide Download History
            @else
                Show Download History
            @endif
        </button>

        @if ($showDownloadHistory)
            <div class="mt-3 card" wire:poll.5s>
                <div class="card-header bg-light">
                    <h6 class="mb-0">Recently Generated Barcodes</h6>
                </div>
                <div class="card-body">
                    @if ($this->recentExports->isEmpty())
                        <div class="text-muted small">No barcode exports found yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Created</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Count</th>
                                        <th>Items/Page</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->recentExports as $export)
                                        <tr>
                                            <td class="text-nowrap">{{ $export->created_at->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="text-nowrap">
                                                @if ($export->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif ($export->status === 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @elseif ($export->status === 'processing')
                                                    <span class="badge bg-primary">Processing</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Queued</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">{{ $export->barcode_type }}</td>
                                            <td class="text-nowrap">{{ $export->selected_count }}</td>
                                            <td class="text-nowrap">{{ $export->items_per_page }}</td>
                                            <td>
                                                @if ($export->status === 'completed')
                                                    <a href="{{ route('barcode.export.download', $export) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-download me-1"></i>Download PDF
                                                    </a>
                                                @elseif ($export->status === 'failed')
                                                    <span class="text-danger small">
                                                        {{ $export->error_message ?? 'Generation failed.' }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Waiting...</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

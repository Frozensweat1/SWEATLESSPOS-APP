<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ $componentName }} | {{ $pageTitle }}</h2>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i>New Adjustment</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Product</label>
                        <select class="form-select" wire:model="product_id">
                            <option value="">Select product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} ({{ $product->stock_quantity }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Adjustment Type</label>
                        <select class="form-select" wire:model="adjustment_type">
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                            <option value="set">Set</option>
                        </select>
                        @error('adjustment_type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" min="0" step="1" class="form-control" wire:model="quantity"
                            placeholder="0">
                        @error('quantity')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" wire:model="reason"
                            placeholder="Optional reason">
                        @error('reason')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" wire:model="notes" placeholder="Optional notes"></textarea>
                        @error('notes')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <button class="btn btn-falcon-primary btn-sm d-flex align-items-center" type="button"
                        wire:click="applyAdjustment" wire:loading.attr="disabled">
                        <span class="fas fa-check" data-fa-transform="shrink-3 down-2"></span>
                        <span class="ms-1">Apply</span>
                        <span wire:loading wire:target="applyAdjustment"
                            class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search reason or notes"
                            wire:model.live="search">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="productFilter">
                            <option value="">All Products</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="typeFilter">
                            <option value="">All Types</option>
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                            <option value="set">Set</option>
                            <option value="sale">Sale</option>
                            <option value="refund">Refund</option>
                            <option value="manual_update">Manual Update</option>
                            <option value="initial">Initial</option>
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Product</th>
                                <th class="text-nowrap">Type</th>
                                <th class="text-nowrap">Before</th>
                                <th class="text-nowrap">After</th>
                                <th class="text-nowrap">Change</th>
                                <th class="text-nowrap">Reason</th>
                                <th class="text-nowrap">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($adjustments as $adjustment)
                                <tr>
                                    <td class="text-nowrap">{{ $adjustment->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $adjustment->product?->name ?? 'N/A' }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $adjustment->type)) }}</td>
                                    <td>{{ $adjustment->quantity_before }}</td>
                                    <td>{{ $adjustment->quantity_after }}</td>
                                    <td
                                        class="{{ $adjustment->change_qty > 0 ? 'text-success' : ($adjustment->change_qty < 0 ? 'text-danger' : '') }}">
                                        {{ $adjustment->change_qty > 0 ? '+' . $adjustment->change_qty : $adjustment->change_qty }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $adjustment->reason ?? 'N/A' }}</div>
                                        @if ($adjustment->notes)
                                            <div class="text-muted small">{{ $adjustment->notes }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $adjustment->user?->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="alert alert-info text-center mb-0">No adjustments found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $adjustments->links() }}
            </div>
        </div>
    </div>
</div>

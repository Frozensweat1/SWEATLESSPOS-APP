<div>
    {{-- The whole world belongs to you. --}}
    <div class="container">
        <!-- Filter Section -->
        <div class="card p-3 mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <label for="productFilter" class="form-label">Product</label>
                    <select id="productFilter" wire:model.live="filters.product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="userFilter" class="form-label">User</label>
                    <select id="userFilter" wire:model.live="filters.user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" wire:model.live="filters.start_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" wire:model.live="filters.end_date" class="form-control">
                </div>
            </div>
        </div>

        <!-- Refunds Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="exportRefunds">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="exportRefunds">ing...</span> Excel</span>
                    <span wire:loading wire:target="exportRefunds" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
                <button class="btn btn-danger btn-sm d-flex" type="button" wire:click="confirmBulkDelete"
                    {{ empty($selectedRefunds) ? 'disabled' : '' }}>
                    <span class="fas fa-trash-alt"></span>
                    <span class="d-none d-sm-inline-block ms-1">Delete Selected</span>
                    <span wire:loading wire:target="confirmBulkDelete" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th><input class="form-check-input" type="checkbox" wire:model.live="selectAll"></th>
                            <th>Sale ID</th>
                            <th>Sale Detail ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Refund Amount</th>
                            <th>Reason</th>
                            <th>User</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                            <tr>
                                <td>
                                    <input class="form-check-input" type="checkbox" wire:model="selectedRefunds"
                                        value="{{ $refund->id }}">
                                </td>
                                <td>{{ $refund->sale_id }}</td>
                                <td>{{ $refund->sale_detail_id }}</td>
                                <td>{{ $refund->product->name ?? 'N/A' }}</td>
                                <td>{{ $refund->quantity }}</td>
                                <td>Ghs {{ number_format($refund->refund_amount, 2) }}</td>
                                <td>{{ $refund->reason }}</td>
                                <td>{{ $refund->user->name ?? 'N/A' }}</td>
                                <td>{{ $refund->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No refunds found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $refunds->links() }}
            </div>
        </div>
    </div>

</div>

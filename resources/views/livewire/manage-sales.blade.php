<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    <div class="container py-4">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">{{ $componentName }} | {{ $pageTitle }}</h2>
            <div class="btn-group">
                <a href="{{ route('make.sales') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">New Sale</span>
                </a>
                <!-- Export Button -->
                <button class="btn btn-falcon-primary btn-sm d-flex" type="button" wire:click="export('Csv')">
                    <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('Csv')">ing...</span> To CSV</span>
                    <span wire:loading wire:target="export('Csv')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="export('Xlsx')">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('Xlsx')">ing...</span> XLSX</span>
                    <span wire:loading wire:target="export('Xlsx')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

            </div>
        </div>

        <!-- Filters Section -->
        <div class="card mb-4">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by Customer"
                        wire:model.live="searchTerm">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="paymentMethodFilter">
                        <option value="">All Payment Methods</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="mobile_payment">Mobile Payment</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filter">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button wire:click="loadDateRange"
                        class="btn btn-sm btn-outline-primary rounded-pill me-1  d-flex align-items-center w-100"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                        <!-- Spinner -->
                        <span wire:loading wire:target="loadDateRange" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>

                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline"><i class="fas fa-recycle me-1"></i>
                            <span wire:loading.remove wire:target="loadDateRange">
                                Apply Filter
                            </span>
                        </span>
                        <span wire:loading wire:target="loadDateRange"> Applying Filter...</span>
                        <span class="d-md-none">
                            <i class="fas fa-recycle me-1"></i>
                        </span></button>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th><input class="form-check-input" type="checkbox" wire:model.live="selectAll"></th>
                            <th class="text-nowrap">Sale ID</th>
                            <th class="text-nowrap">Customer</th>
                            <th class="text-nowrap">Product</th>
                            <th class="text-nowrap">Quantity</th>
                            <th class="text-nowrap">Total Amount</th>
                            <th class="text-nowrap">Payment Method</th>
                            <th class="text-nowrap">Date</th>
                            <th class="text-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            @foreach ($sale->saleDetails as $detail)
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" wire:model="selectedSales"
                                            value="{{ $sale->id }}">
                                    </td>
                                    <td>{{ $sale->id }}</td>
                                    <td>
                                        {{ $sale->customer ? $sale->customer->name : 'Guest' }}
                                    </td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Ghs {{ number_format($detail->subtotal, 2) }}</td>
                                    <td>{{ ucfirst($sale->payment_method) }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>

                                    <td class="align-middle white-space-nowrap py-2 text-end">
                                        <div class="dropdown font-sans-serif position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                                <span class="fas fa-ellipsis-h fs--1"></span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end border py-0"
                                                aria-labelledby="customer-dropdown-0">
                                                <div class="bg-white py-2">

                                                    <a class="dropdown-item text-success"
                                                        wire:click="Refund({{ $detail->id }})"
                                                        href="javascript:void(0)">Refund
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bulk Actions and Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="btn-group">
                <button class="btn btn-outline-danger btn-sm d-flex" wire:click="bulkDelete">
                    <span class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span>
                    Delete Selected
                    <span wire:loading wire:target="bulkDelete" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
            {{ $sales->links() }}
            <!-- Pagination links -->
        </div>
    </div>
    @if ($isRefundMode)
        @include('livewire.refund-formmodal')
    @endif
</div>

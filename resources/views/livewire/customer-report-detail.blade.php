<div>
    {{-- Stop trying to control. --}}
    <div class="container mt-4">
        <!-- Customer Header -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $customer->name }}'s Purchase History</h5>
                <p class="card-text">Total Purchases: {{ $customer->sales->count() }}</p>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter by Date Range</h5>
                <div class="row g-2 align-items-center">
                    <!-- Start Date -->
                    <div class="col-md-6">
                        <input type="date" class="form-control" wire:model.live="startDate" placeholder="Start Date">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6">
                        <input type="date" class="form-control" wire:model.live="endDate" placeholder="End Date">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
            <div class="card-header">
                <h5>Purchase History</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            @foreach ($sale->saleDetails as $saleDetail)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $saleDetail->product->name ?? 'N/A' }}</td>
                                    <td>{{ $saleDetail->quantity }}</td>
                                    <td>${{ number_format($saleDetail->subtotal, 2) }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No purchases found for the selected date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between">
                <span>Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }}
                    sales</span>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>

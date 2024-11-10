<div>
    <div class="container mt-4">
        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Filters</h5>
                <div class="row g-2 align-items-center">
                    <!-- Search -->
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Search by customer name..."
                            wire:model.live="search">
                    </div>

                    <!-- Sort Options -->
                    <div class="col-md-6">
                        <select class="form-select" wire:model.live="sortField">
                            <option value="total_amount">Sort by Total Purchase Amount</option>
                            <option value="created_at">Sort by Recent Purchase Date</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="card">
            <div class="card-header">
                <h5>Customer Report Summary</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Total Purchase Amount</th>
                            <th>Total Purchases</th>
                            <th>Last Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('customerreport.detail', $customer->id) }}">
                                        {{ $customer->name }}
                                    </a>
                                </td>
                                <td>GHS {{ number_format($customer->sales->sum('total_amount'), 2) }}</td>
                                <td>{{ $customer->sales->count() }}</td>
                                <td>{{ $customer->sales->last()?->created_at ? $customer->sales->last()->created_at->format('Y-m-d') : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between">
                <span>Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }}
                    customers</span>
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>

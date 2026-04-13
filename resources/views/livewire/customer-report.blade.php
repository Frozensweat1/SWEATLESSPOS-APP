<div>
    <div class="container mt-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search by name, email, phone..."
                            wire:model.live="search">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="sortField">
                            <option value="total_spent">Sort by Total Spent</option>
                            <option value="total_orders">Sort by Orders</option>
                            <option value="created_at">Sort by Date</option>
                        </select>
                    </div>
                    <div class="col-md-5 text-end">
                        <button class="btn btn-falcon-primary btn-sm" wire:click="export('csv')">
                            <span class="fas fa-file-csv me-1"></span> CSV
                        </button>
                        <button class="btn btn-falcon-success btn-sm" wire:click="export('xlsx')">
                            <span class="fas fa-file-excel me-1"></span> Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Customers</h6>
                        <h4 class="mb-0">{{ number_format($stats['totalCustomers']) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h4 class="mb-0">Ghs {{ number_format($stats['totalRevenue'], 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Avg Customer Value</h6>
                        <h4 class="mb-0">Ghs {{ number_format($stats['avgCustomerValue'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Customer Report</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Tier</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                            <th>Avg Order</th>
                            <th>Last Purchase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('customerreport.detail', $item->customer->id) }}">
                                        {{ $item->customer->name }}
                                    </a>
                                    <br><small class="text-muted">{{ $item->customer->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $item->tier == 'VIP' ? 'warning' : ($item->tier == 'Premium' ? 'info' : ($item->tier == 'Regular' ? 'success' : 'secondary')) }}">
                                        {{ $item->tier }}
                                    </span>
                                </td>
                                <td>{{ $item->totalOrders }}</td>
                                <td>Ghs {{ number_format($item->totalSpent, 2) }}</td>
                                <td>Ghs {{ number_format($item->avgOrderValue, 2) }}</td>
                                <td>{{ $item->lastPurchase?->format('Y-m-d') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No customers found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <span class="text-muted">Showing {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers</span>
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
<div>
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-falcon-success btn-sm" wire:click="export">
                            <span class="fas fa-file-excel me-1"></span> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Staff</h6>
                        <h4 class="mb-0">{{ $summary['totalStaff'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['totalRevenue'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Total Transactions</h6>
                        <h4 class="mb-0">{{ number_format($summary['totalTransactions'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Top Performer</h6>
                        <h5 class="mb-0">{{ $summary['topPerformer']?->user->name ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Staff Performance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff Name</th>
                                <th>Email</th>
                                <th>Transactions</th>
                                <th>Revenue</th>
                                <th>Items Sold</th>
                                <th>Refunds</th>
                                <th>Avg Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffStats as $index => $staff)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $staff->user->name }}</strong>
                                        @if($index === 0)
                                            <span class="badge bg-warning ms-1">Top</span>
                                        @endif
                                    </td>
                                    <td>{{ $staff->user->email }}</td>
                                    <td>{{ $staff->transactionCount }}</td>
                                    <td>Ghs {{ number_format($staff->totalRevenue, 2) }}</td>
                                    <td>{{ $staff->totalItems }}</td>
                                    <td class="{{ $staff->refunds > 0 ? 'text-danger' : '' }}">
                                        Ghs {{ number_format($staff->refunds, 2) }}
                                    </td>
                                    <td>Ghs {{ number_format($staff->avgTransaction, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">No staff data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
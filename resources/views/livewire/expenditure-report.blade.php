<div>
    <div class="container py-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" wire:model.live="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-falcon-success btn-sm" wire:click="export">
                            <span class="fas fa-file-excel me-1"></span> Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6>Total Expenditure</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['total'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Transactions</h6>
                        <h4 class="mb-0">{{ number_format($summary['count'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Average</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['average'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Categories Used</h6>
                        <h4 class="mb-0">{{ $summary['byCategory']->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if($summary['byCategory']->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5>By Category</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($summary['byCategory'] as $cat)
                    <div class="col-md-3 mb-2">
                        <div class="border rounded p-2">
                            <small class="text-muted">{{ $cat->category ?? 'Uncategorized' }}</small>
                            <h6 class="mb-0">Ghs {{ number_format($cat->total, 2) }}</h6>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>Expenditure Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenditures as $exp)
                                <tr>
                                    <td>{{ $exp->expenditure_date->format('Y-m-d') }}</td>
                                    <td>{{ $exp->description }}</td>
                                    <td><span class="badge bg-secondary">{{ $exp->category ?? 'N/A' }}</span></td>
                                    <td class="text-danger">Ghs {{ number_format($exp->amount, 2) }}</td>
                                    <td>{{ $exp->user->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No expenditures found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $expenditures->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
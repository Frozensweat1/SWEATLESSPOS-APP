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
                        <h6>Active Categories</h6>
                        <h4 class="mb-0">{{ $summary['totalCategories'] }}</h4>
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
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Total Profit</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['totalProfit'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Top Category</h6>
                        <h5 class="mb-0">{{ $summary['topCategory']?->category->name ?? 'N/A' }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Category Performance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Qty Sold</th>
                                <th>Revenue</th>
                                <th>Cost</th>
                                <th>Profit</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryStats as $index => $cat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $cat->category->name }}</strong>
                                        @if($index === 0)
                                            <span class="badge bg-warning ms-1">Top</span>
                                        @endif
                                    </td>
                                    <td>{{ $cat->quantitySold }}</td>
                                    <td>Ghs {{ number_format($cat->revenue, 2) }}</td>
                                    <td>Ghs {{ number_format($cat->cost, 2) }}</td>
                                    <td class="{{ $cat->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                        Ghs {{ number_format($cat->profit, 2) }}
                                    </td>
                                    <td>
                                        @php $margin = $cat->revenue > 0 ? ($cat->profit / $cat->revenue) * 100 : 0; @endphp
                                        <span class="badge bg-{{ $margin >= 30 ? 'success' : ($margin >= 15 ? 'info' : 'warning') }}">
                                            {{ number_format($margin, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No category data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
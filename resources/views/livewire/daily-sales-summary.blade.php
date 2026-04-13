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
                        <label class="form-label">Group By</label>
                        <select class="form-select" wire:model.live="groupBy">
                            <option value="day">Daily</option>
                            <option value="week">Weekly</option>
                            <option value="month">Monthly</option>
                            <option value="hour">Hourly</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Revenue</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['totalRevenue'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Transactions</h6>
                        <h4 class="mb-0">{{ number_format($summary['transactionCount'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Daily Average</h6>
                        <h4 class="mb-0">Ghs {{ number_format($summary['averageDaily'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6>Best Day</h6>
                        <h5 class="mb-0">
                            @if($summary['bestDay'] ?? null)
                                {{ $summary['bestDay']->period }}<br>
                                <small>Ghs {{ number_format($summary['bestDay']->total_revenue, 0) }}</small>
                            @else
                                N/A
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Sales Over Time</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ $groupBy === 'hour' ? 'Hour' : ($groupBy === 'week' ? 'Week' : ($groupBy === 'month' ? 'Month' : 'Date')) }}</th>
                                <th>Transactions</th>
                                <th>Total Revenue</th>
                                <th>Cash</th>
                                <th>Mobile</th>
                                <th>Card</th>
                                <th>Avg per Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySales as $day)
                                <tr>
                                    <td>{{ $day->period }}</td>
                                    <td>{{ $day->transaction_count }}</td>
                                    <td>Ghs {{ number_format($day->total_revenue, 2) }}</td>
                                    <td>Ghs {{ number_format($day->cash_total ?? 0, 2) }}</td>
                                    <td>Ghs {{ number_format($day->mobile_total ?? 0, 2) }}</td>
                                    <td>Ghs {{ number_format($day->card_total ?? 0, 2) }}</td>
                                    <td>Ghs {{ number_format($day->transaction_count > 0 ? $day->total_revenue / $day->transaction_count : 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No sales data found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
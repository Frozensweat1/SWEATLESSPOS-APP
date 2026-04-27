<div>
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Financial Report Filters</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="startDate" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-4">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-4">
                        <label for="userId">User</label>
                        <select id="userId" class="form-select" wire:model.live="userId">
                            <option value="">All Users</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Sales</h6>
                        <h4 class="mb-0">Ghs {{ number_format($totalSales, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Expenditures</h6>
                        <h4 class="mb-0">Ghs {{ number_format($totalExpenditures, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h6 class="card-title">Total Refunded</h6>
                        <h4 class="mb-0">Ghs {{ number_format($totalRefunded, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Inventory Value</h6>
                        <h4 class="mb-0">GH₵ {{ number_format($totalInventoryValue, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Net Profit</h6>
                        <h4 class="mb-0 {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            Ghs {{ number_format($netProfit, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Gross Margin</h6>
                        <h4 class="mb-0">{{ number_format($grossMargin, 1) }}%</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Avg Transaction</h6>
                        <h4 class="mb-0">Ghs {{ number_format($averageTransaction, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">Inventory Cost</h6>
                        <h4 class="mb-0">Ghs {{ number_format($totalInventoryCost, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if ($salesByPaymentMethod->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($salesByPaymentMethod as $payment)
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6 class="text-muted">{{ ucfirst($payment->payment_method) }}</h6>
                                    <h5>Ghs {{ number_format($payment->total, 2) }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>Sales and Expenditures Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td><span class="badge bg-success">Sale</span></td>
                                    <td>Ghs {{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ $sale->customer?->name ?? 'Guest' }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No sales found</td>
                                </tr>
                            @endforelse
                            @forelse($expenditures as $expenditure)
                                <tr>
                                    <td>{{ $expenditure->id }}</td>
                                    <td><span class="badge bg-danger">Expenditure</span></td>
                                    <td>Ghs {{ number_format($expenditure->amount, 2) }}</td>
                                    <td>{{ $expenditure->description }}</td>
                                    <td>{{ $expenditure->expenditure_date->format('Y-m-d') }}</td>
                                    <td>{{ $expenditure->user->name ?? 'N/A' }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    {{-- Filter Form --}}
    <div class="container">
        <!-- Filter Card -->
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

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text">Ghs {{ number_format($totalSales, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenditures</h5>
                        <p class="card-text">Ghs {{ number_format($totalExpenditures, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales and Expenditures Table -->
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
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td>Sale</td>
                                    <td>Ghs {{ number_format($sale->total_amount, 2) }}</td>
                                    <td>Sale Transaction</td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            @foreach ($expenditures as $expenditure)
                                <tr>
                                    <td>{{ $expenditure->id }}</td>
                                    <td>Expenditure</td>
                                    <td>Ghs {{ number_format($expenditure->amount, 2) }}</td>
                                    <td>{{ $expenditure->description }}</td>
                                    <td>{{ $expenditure->expenditure_date->format('Y-m-d') }}</td>
                                    <td>{{ $expenditure->user->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Single Pagination for Sales and Expenditures -->
                <div class="d-flex justify-content-between mt-3">
                    {{ $sales->links() }} <!-- This handles the sales pagination -->
                </div>
            </div>
        </div>
    </div>
</div>

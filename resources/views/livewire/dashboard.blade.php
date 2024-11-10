<div>
    {{-- Be like water. --}}
    <div class="container mt-5">
        <div class="row">
            <!-- Sales Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text display-4">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>

            <!-- Sale Details Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Sale Details</h5>
                        <p class="card-text display-4">{{ $totalSaleDetails }}</p>
                    </div>
                </div>
            </div>

            <!-- Expenditures Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenditures</h5>
                        <p class="card-text display-4">{{ $totalExpenditures }}</p>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text display-4">{{ $totalProducts }}</p>
                    </div>
                </div>
            </div>

            <!-- Notifications Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Total Notifications</h5>
                        <p class="card-text display-4">{{ $totalNotifications }}</p>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Revenue</h5>
                        <p class="card-text display-4">Ghs {{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <p class="card-text display-4">Ghs {{ number_format($totalExpenses, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

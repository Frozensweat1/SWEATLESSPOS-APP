<div>
    
    <div class="container mt-5">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-sm-5">
                        <label class="form-label mb-1" for="dashboard-from-date">From</label>
                        <input id="dashboard-from-date" type="date" class="form-control"
                            wire:model.defer="fromDate">
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label mb-1" for="dashboard-to-date">To</label>
                        <input id="dashboard-to-date" type="date" class="form-control"
                            wire:model.defer="toDate">
                    </div>
                    <div class="col-sm-2 d-grid">
                        <button class="btn btn-primary d-flex align-items-center justify-content-center"
                            type="button" wire:click="applyDateFilter" wire:loading.attr="disabled"
                            wire:target="applyDateFilter">
                            <span wire:loading wire:target="applyDateFilter"
                                class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <i class="fas fa-filter me-2" wire:loading.remove wire:target="applyDateFilter"></i>
                            <span wire:loading.remove wire:target="applyDateFilter">Apply</span>
                            <span wire:loading wire:target="applyDateFilter">Applying...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sales Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text display-4"><?php echo e($totalSales); ?></p>
                    </div>
                </div>
            </div>

            <!-- Sale Details Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Sale Details</h5>
                        <p class="card-text display-4"><?php echo e($totalSaleDetails); ?></p>
                    </div>
                </div>
            </div>

            <!-- Expenditures Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenditures</h5>
                        <p class="card-text display-4"><?php echo e($totalExpenditures); ?></p>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text display-4"><?php echo e($totalProducts); ?></p>
                    </div>
                </div>
            </div>

            <!-- Notifications Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Total Notifications</h5>
                        <p class="card-text display-4"><?php echo e($totalNotifications); ?></p>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4"><?php echo e($totalUsers); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Revenue</h5>
                        <p class="card-text display-4">Ghs <?php echo e(number_format($totalRevenue, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <p class="card-text display-4">Ghs <?php echo e(number_format($totalExpenses, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Gross Profit Margin Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Gross Profit Margin</h5>
                        <p class="card-text display-4">Ghs <?php echo e(number_format($grossProfit, 2)); ?></p>
                    </div>
                </div>
            </div>

            <!-- Net Profit Margin Card -->
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Net Profit Margin</h5>
                        <p class="card-text display-4">Ghs <?php echo e(number_format($netProfit, 2)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>
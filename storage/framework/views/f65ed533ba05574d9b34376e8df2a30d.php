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
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('customerreport.detail', $customer->id)); ?>">
                                        <?php echo e($customer->name); ?>

                                    </a>
                                </td>
                                <td>GHS <?php echo e(number_format($customer->sales->sum('total_amount'), 2)); ?></td>
                                <td><?php echo e($customer->sales->count()); ?></td>
                                <td><?php echo e($customer->sales->last()?->created_at ? $customer->sales->last()->created_at->format('Y-m-d') : 'N/A'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between">
                <span>Showing <?php echo e($customers->firstItem()); ?> to <?php echo e($customers->lastItem()); ?> of <?php echo e($customers->total()); ?>

                    customers</span>
                <?php echo e($customers->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/customer-report.blade.php ENDPATH**/ ?>
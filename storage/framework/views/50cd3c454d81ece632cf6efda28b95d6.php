<div>
    
    <div class="container mt-4">
        <!-- Customer Header -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo e($customer->name); ?>'s Purchase History</h5>
                <p class="card-text">Total Purchases: <?php echo e($customer->sales->count()); ?></p>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter by Date Range</h5>
                <div class="row g-2 align-items-center">
                    <!-- Start Date -->
                    <div class="col-md-6">
                        <input type="date" class="form-control" wire:model.live="startDate" placeholder="Start Date">
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6">
                        <input type="date" class="form-control" wire:model.live="endDate" placeholder="End Date">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
            <div class="card-header">
                <h5>Purchase History</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sale->saleDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($sale->id); ?></td>
                                    <td><?php echo e($saleDetail->product->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($saleDetail->quantity); ?></td>
                                    <td>$<?php echo e(number_format($saleDetail->subtotal, 2)); ?></td>
                                    <td><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">No purchases found for the selected date range.
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between">
                <span>Showing <?php echo e($sales->firstItem()); ?> to <?php echo e($sales->lastItem()); ?> of <?php echo e($sales->total()); ?>

                    sales</span>
                <?php echo e($sales->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/customer-report-detail.blade.php ENDPATH**/ ?>
<div>
    
    <div class="container">
        <!-- Filter Section -->
        <div class="card p-3 mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <label for="productFilter" class="form-label">Product</label>
                    <select id="productFilter" wire:model.live="filters.product_id" class="form-select">
                        <option value="">All Products</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="userFilter" class="form-label">User</label>
                    <select id="userFilter" wire:model.live="filters.user_id" class="form-select">
                        <option value="">All Users</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="startDate" class="form-label">Start Date</label>
                    <input type="date" id="startDate" wire:model.live="filters.start_date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="endDate" class="form-label">End Date</label>
                    <input type="date" id="endDate" wire:model.live="filters.end_date" class="form-control">
                </div>
            </div>
        </div>

        <!-- Refunds Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="exportRefunds">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="exportRefunds">ing...</span> Excel</span>
                    <span wire:loading wire:target="exportRefunds" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
                <button class="btn btn-danger btn-sm d-flex" type="button" wire:click="confirmBulkDelete"
                    <?php echo e(empty($selectedRefunds) ? 'disabled' : ''); ?>>
                    <span class="fas fa-trash-alt"></span>
                    <span class="d-none d-sm-inline-block ms-1">Delete Selected</span>
                    <span wire:loading wire:target="confirmBulkDelete" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th><input class="form-check-input" type="checkbox" wire:model.live="selectAll"></th>
                            <th>Sale ID</th>
                            <th>Sale Detail ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Refund Amount</th>
                            <th>Reason</th>
                            <th>User</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <input class="form-check-input" type="checkbox" wire:model="selectedRefunds"
                                        value="<?php echo e($refund->id); ?>">
                                </td>
                                <td><?php echo e($refund->sale_id); ?></td>
                                <td><?php echo e($refund->sale_detail_id); ?></td>
                                <td><?php echo e($refund->product->name ?? 'N/A'); ?></td>
                                <td><?php echo e($refund->quantity); ?></td>
                                <td>Ghs <?php echo e(number_format($refund->refund_amount, 2)); ?></td>
                                <td><?php echo e($refund->reason); ?></td>
                                <td><?php echo e($refund->user->name ?? 'N/A'); ?></td>
                                <td><?php echo e($refund->created_at->format('Y-m-d')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center">No refunds found.</td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <?php echo e($refunds->links()); ?>

            </div>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-returns.blade.php ENDPATH**/ ?>
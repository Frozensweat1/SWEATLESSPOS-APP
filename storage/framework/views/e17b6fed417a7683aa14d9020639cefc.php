<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><?php echo e($componentName); ?> | <?php echo e($pageTitle); ?></h2>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-sliders-h me-2"></i>New Adjustment</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Product</label>
                        <select class="form-select" wire:model="product_id">
                            <option value="">Select product</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>">
                                    <?php echo e($product->name); ?> (<?php echo e($product->stock_quantity); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger small"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Adjustment Type</label>
                        <select class="form-select" wire:model="adjustment_type">
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                            <option value="set">Set</option>
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['adjustment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger small"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="number" min="0" step="1" class="form-control" wire:model="quantity"
                            placeholder="0">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger small"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reason</label>
                        <input type="text" class="form-control" wire:model="reason"
                            placeholder="Optional reason">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger small"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="2" wire:model="notes" placeholder="Optional notes"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger small"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <button class="btn btn-falcon-primary btn-sm d-flex align-items-center" type="button"
                        wire:click="applyAdjustment" wire:loading.attr="disabled">
                        <span class="fas fa-check" data-fa-transform="shrink-3 down-2"></span>
                        <span class="ms-1">Apply</span>
                        <span wire:loading wire:target="applyAdjustment"
                            class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search reason or notes"
                            wire:model.live="search">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="productFilter">
                            <option value="">All Products</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="typeFilter">
                            <option value="">All Types</option>
                            <option value="increase">Increase</option>
                            <option value="decrease">Decrease</option>
                            <option value="set">Set</option>
                            <option value="sale">Sale</option>
                            <option value="refund">Refund</option>
                            <option value="manual_update">Manual Update</option>
                            <option value="initial">Initial</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="endDate">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Product</th>
                                <th class="text-nowrap">Type</th>
                                <th class="text-nowrap">Before</th>
                                <th class="text-nowrap">After</th>
                                <th class="text-nowrap">Change</th>
                                <th class="text-nowrap">Reason</th>
                                <th class="text-nowrap">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $adjustments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adjustment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-nowrap"><?php echo e($adjustment->created_at->format('Y-m-d H:i')); ?></td>
                                    <td><?php echo e($adjustment->product?->name ?? 'N/A'); ?></td>
                                    <td><?php echo e(ucwords(str_replace('_', ' ', $adjustment->type))); ?></td>
                                    <td><?php echo e($adjustment->quantity_before); ?></td>
                                    <td><?php echo e($adjustment->quantity_after); ?></td>
                                    <td
                                        class="<?php echo e($adjustment->change_qty > 0 ? 'text-success' : ($adjustment->change_qty < 0 ? 'text-danger' : '')); ?>">
                                        <?php echo e($adjustment->change_qty > 0 ? '+' . $adjustment->change_qty : $adjustment->change_qty); ?>

                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($adjustment->reason ?? 'N/A'); ?></div>
                                        <!--[if BLOCK]><![endif]--><?php if($adjustment->notes): ?>
                                            <div class="text-muted small"><?php echo e($adjustment->notes); ?></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td><?php echo e($adjustment->user?->name ?? 'System'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="alert alert-info text-center mb-0">No adjustments found.</div>
                                    </td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($adjustments->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-stock-adjustments.blade.php ENDPATH**/ ?>
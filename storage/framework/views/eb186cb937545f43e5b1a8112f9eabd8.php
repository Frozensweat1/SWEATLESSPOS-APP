<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><?php echo e($componentName); ?> | <?php echo e($pageTitle); ?></h2>
            <div class="text-muted small">
                Threshold: <?php echo e($lowStockThreshold); ?>

            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search products..."
                        wire:model.live="search">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="supplierFilter">
                        <option value="">All Suppliers</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="categoryFilter">
                        <option value="">All Categories</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Product</th>
                                <th class="text-nowrap">SKU</th>
                                <th class="text-nowrap">Supplier</th>
                                <th class="text-nowrap">Stock</th>
                                <th class="text-nowrap">Threshold</th>
                                <th class="text-nowrap">Reorder Qty</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $shownSuppliers = [];
                            ?>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $supplierId = $product->supplier_id;
                                    $reorderQty = max(0, $lowStockThreshold - (int) $product->stock_quantity);
                                ?>
                                <tr>
                                    <td class="text-nowrap"><?php echo e($product->name); ?></td>
                                    <td class="text-nowrap"><?php echo e($product->sku); ?></td>
                                    <td class="text-nowrap"><?php echo e($product->supplier?->name ?? 'N/A'); ?></td>
                                    <td class="text-nowrap"><?php echo e($product->stock_quantity); ?></td>
                                    <td class="text-nowrap"><?php echo e($lowStockThreshold); ?></td>
                                    <td class="text-nowrap"><?php echo e($reorderQty); ?></td>
                                    <td class="text-nowrap">
                                        <!--[if BLOCK]><![endif]--><?php if($supplierId && !in_array($supplierId, $shownSuppliers, true)): ?>
                                            <button class="btn btn-falcon-primary btn-sm d-flex align-items-center"
                                                type="button" wire:click="createPurchaseOrder(<?php echo e($supplierId); ?>)"
                                                wire:loading.attr="disabled"
                                                wire:target="createPurchaseOrder(<?php echo e($supplierId); ?>)">
                                                <span class="fas fa-file-invoice-dollar"
                                                    data-fa-transform="shrink-3 down-2"></span>
                                                <span class="ms-1">Create PO</span>
                                                <span wire:loading
                                                    wire:target="createPurchaseOrder(<?php echo e($supplierId); ?>)"
                                                    class="spinner-border spinner-border-sm ms-2" role="status"
                                                    aria-hidden="true"></span>
                                            </button>
                                            <?php
                                                $shownSuppliers[] = $supplierId;
                                            ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-info text-center mb-0">No low stock items found.</div>
                                    </td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-reorder-alerts.blade.php ENDPATH**/ ?>
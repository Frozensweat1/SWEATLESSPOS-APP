<div>
    

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Inventory Report</h4>
            <div class="btn-group">
                <!-- Export Button -->
                <button class="btn btn-falcon-primary btn-sm d-flex" type="button" wire:click="export('csv')">
                    <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('csv')">ing...</span> To CSV</span>
                    <span wire:loading wire:target="export('csv')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="export('xlsx')">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('xlsx')">ing...</span> Excel</span>
                    <span wire:loading wire:target="export('xlsx')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control"
                        placeholder="Search products...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="supplierFilter" class="form-select">
                        <option value="">All Suppliers</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input type="checkbox" wire:model.live="lowStock" class="form-check-input" id="lowStock">
                        <label class="form-check-label" for="lowStock">Low Stock</label>
                    </div>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table table-sm table-striped table-responsive fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Price</th>
                            <th scope="col">Cost Price</th>
                            <th class="text-nowrap" scope="col">Stock Quantity</th>
                            <th class="text-nowrap" scope="col">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-nowrap"><?php echo e($product->name); ?></td>
                                <td class="text-nowrap"><?php echo e($product->sku); ?></td>
                                <td class="text-nowrap"><?php echo e($product->barcode); ?></td>
                                <td class="text-nowrap"><?php echo e($product->category->name ?? 'N/A'); ?></td>
                                <td class="text-nowrap"><?php echo e($product->supplier->name ?? 'N/A'); ?></td>
                                <td class="text-nowrap">Ghs <?php echo e($product->price); ?></td>
                                <td class="text-nowrap">Ghs <?php echo e($product->cost_price); ?></td>
                                <td class="text-nowrap"><?php echo e($product->stock_quantity); ?></td>
                                <td class="text-nowrap">
                                    <?php echo e($product->expiry_date ? $product->expiry_date->format('Y-m-d') : 'N/A'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center">No products found.</td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                <?php echo e($products->links()); ?>

            </div>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/inventory-report.blade.php ENDPATH**/ ?>
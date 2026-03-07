<div>
    

    <div class="card mb-3" id="customersTable">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0"><?php echo e($componentName); ?> | <?php echo e($pageTitle); ?></h5>
                </div>

                <div class="col-8 col-sm-auto text-end ps-2">
                    <div id="table-suppliers-replace-element">
                        <div class="btn-group">
                            <!-- New Button -->
                            <button href="javascript:void(0)" class="btn btn-falcon-default btn-sm d-flex" type="button"
                                wire:click="create">
                                <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                                <span class="d-none d-sm-inline-block ms-1">New Product</span>
                                <span wire:loading wire:target="create" class="spinner-border spinner-border-sm ms-1"
                                    role="status" aria-hidden="true"></span>
                            </button>

                            <!-- Export Button -->
                            <button class="btn btn-falcon-primary btn-sm d-flex" type="button"
                                wire:click="export('Csv')">
                                <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                                <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                                        wire:target="export('Csv')">ing...</span> CSV</span>
                                <span wire:loading wire:target="export('Csv')"
                                    class="spinner-border spinner-border-sm ms-1" role="status"
                                    aria-hidden="true"></span>
                            </button>

                            <button class="btn btn-falcon-success btn-sm d-flex" type="button"
                                wire:click="export('Xlsx')">
                                <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                                <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                                        wire:target="export('Xlsx')">ing...</span> Excel</span>
                                <span wire:loading wire:target="export('Xlsx')"
                                    class="spinner-border spinner-border-sm ms-1" role="status"
                                    aria-hidden="true"></span>
                            </button>

                            <!-- Bulk Delete Button -->
                            <button class="btn btn-danger btn-sm d-flex" type="button" wire:click="confirmBulkDelete"
                                <?php echo e(empty($selectedProducts) ? 'disabled' : ''); ?>>
                                <span class="fas fa-trash-alt"></span>
                                <span class="d-none d-sm-inline-block ms-1">Delete Selected</span>
                                <span wire:loading wire:target="confirmBulkDelete"
                                    class="spinner-border spinner-border-sm ms-1" role="status"
                                    aria-hidden="true"></span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row mb-3 p-2">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Search..." wire:model.live="searchengine">
                </div>
                <div class="col">
                    <select class="form-control" wire:model.live="categoryFilter">
                        <option value="">All Categories</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col">
                    <select class="form-control" wire:model.live="supplierFilter">
                        <option value="">All Suppliers</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
                <div class="col">
                    <input type="date" class="form-control" wire:model.live="dateFrom">
                </div>
                <div class="col">
                    <input type="date" class="form-control" wire:model.live="dateTo">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">

                        <tr>
                            <th> <input class="form-check-input" type="checkbox" wire:model.live="selectAll"> </th>
                            <th class="text-nowrap" scope="col">Name</th>
                            <th class="text-nowrap" scope="col">Barcode</th>
                            <th class="text-nowrap" scope="col">SKU</th>
                            <th class="text-nowrap" scope="col">Price</th>
                            <th class="w-200" scope="col" style="min-width: 200px;">Description</th>
                            <th class="text-nowrap" scope="col">Cost Price</th>
                            <th class="text-nowrap" scope="col">Stock Qty</th>
                            <th class="text-nowrap" scope="col">Category</th>
                            <th class="text-nowrap" scope="col">Supplier</th>
                            <th class="text-nowrap" scope="col">Manufacture Date</th>
                            <th class="text-nowrap" scope="col">Expiry Date</th>
                            <th class="text-nowrap" scope="col">Created At</th>
                            <th class="text-nowrap" scope="col">Action</th>
                        </tr>

                    </thead>
                    <tbody class="list" id="table-customers-body">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="btn-reveal-trigger">
                                <td class="text-nowrap">
                                    <input class="form-check-input" type="checkbox" wire:model="selectedProducts"
                                        value="<?php echo e($product->id); ?>">
                                </td>
                                <td class="text-nowrap">
                                    <a wire:click="Edit(<?php echo e($product->id); ?>)" href="javascript:void(0)">
                                        <div class="d-flex d-flex align-items-center">
                                            <div class="avatar avatar-xl me-2">
                                                <!--[if BLOCK]><![endif]--><?php if($product->image): ?>
                                                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>"
                                                        alt="<?php echo e($product->name); ?>" class="rounded-circle">
                                                <?php else: ?>
                                                    <div class="avatar-name rounded-circle">
                                                        <span>
                                                            <?php echo e(collect(explode(' ', $product->name))->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('')); ?>

                                                        </span>
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>

                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1"><?php echo e($product->name); ?></h5>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="text-nowrap"> <?php echo e($product->barcode); ?> </td>
                                <td class="text-nowrap"> <?php echo e($product->sku); ?> </td>
                                <td class="text-nowrap"> Ghs <?php echo e($product->price); ?> </td>
                                <td class="w-200"> <?php echo e($product->description); ?> </td>
                                <td class="text-nowrap">Ghs <?php echo e($product->cost_price); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->stock_quantity); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->category->name); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->supplier->name); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->manufacture_date->format('Y-m-d')); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->expiry_date->format('Y-m-d')); ?> </td>
                                <td class="text-nowrap"><?php echo e($product->created_at->format('Y-m-d')); ?></td>
                                <td class="align-middle white-space-nowrap py-2 text-end">
                                    <div class="dropdown font-sans-serif position-static">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                            type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                            data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="customer-dropdown-0">
                                            <div class="bg-white py-2">
                                                <a class="dropdown-item" wire:click="Edit(<?php echo e($product->id); ?>)"
                                                    href="javascript:void(0)" type="button">Edit</a>
                                                <a class="dropdown-item text-danger"
                                                    wire:click="Delete(<?php echo e($product->id); ?>)"
                                                    href="javascript:void(0)">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="14">
                                    <div class="alert alert-info text-center" role="alert">No record found!</div>
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-center">
            <!-- Pagination -->
            <?php echo e($products->links()); ?>


        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($isEditMode): ?>
        <?php echo $__env->make('livewire.product-formmodal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-product.blade.php ENDPATH**/ ?>
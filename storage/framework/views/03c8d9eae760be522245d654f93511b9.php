<div>
    <div class="px-2 py-3 container-fluid px-sm-3 px-md-4">
        <div class="mb-4 row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-0"><?php echo e($pageTitle); ?> > <?php echo e($componentName); ?></h1>
                        <p class="mt-1 text-muted small">Manage all store locations and configurations</p>
                    </div>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Store
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-4 card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.live="searchengine" class="form-control"
                            placeholder="Search by store name, email, or phone...">
                    </div>
                    <div class="gap-2 col-md-6 d-flex">
                        <button class="btn btn-outline-danger" <?php if(count($selectedStores) === 0): echo 'disabled'; endif; ?> wire:click="bulkDelete">
                            <i class="fas fa-trash me-2"></i>Delete Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stores Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="w-5">
                                <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                            </th>
                            <th>Store Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Initial Balance</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="selectedStores" value="<?php echo e($store->id); ?>"
                                        class="form-check-input" <?php if($store->id === 1): ?> disabled <?php endif; ?>>
                                </td>
                                <td>
                                    <div class="gap-2 d-flex align-items-center">
                                        <!--[if BLOCK]><![endif]--><?php if($store->logo_path): ?>
                                            <img src="<?php echo e(asset('storage/' . $store->logo_path)); ?>"
                                                alt="<?php echo e($store->name); ?>" class="rounded-circle" width="32"
                                                height="32">
                                        <?php else: ?>
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-store fa-sm"></i>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <div>
                                            <p class="mb-0"><?php echo e($store->name); ?></p>
                                            <small class="text-muted"><?php echo e($store->address); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo e($store->email); ?></td>
                                <td><?php echo e($store->phone); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo e(config('app.currency_symbol', 'GHS')); ?><?php echo e(number_format($store->initial_balance, 2)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge <?php if($store->is_active): ?> bg-success <?php else: ?> bg-danger <?php endif; ?>">
                                        <?php echo e($store->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                    <!--[if BLOCK]><![endif]--><?php if($store->id === 1): ?>
                                        <span class="badge bg-warning ms-1">Default</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" wire:click="Edit(<?php echo e($store->id); ?>)"
                                        title="Edit Store">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!--[if BLOCK]><![endif]--><?php if($store->id !== 1): ?>
                                        <button class="btn btn-sm btn-danger" wire:click="Delete(<?php echo e($store->id); ?>)"
                                            title="Delete Store">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-4 text-center">
                                    <p class="mb-0 text-muted">
                                        <i class="mb-2 fas fa-inbox fa-2x d-block"></i>
                                        No stores found
                                    </p>
                                </td>
                            </tr>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Showing <?php echo e($stores->count()); ?> of <?php echo e($stores->total()); ?>

                            stores
                        </p>
                    </div>
                    <div>
                        <?php echo e($stores->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Modal -->
    <div class="modal <?php if($isEditMode): ?> show <?php endif; ?>"
        <?php if($isEditMode): ?> style="display: block; background-color: rgba(0,0,0,.5);" <?php endif; ?>
        tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <?php echo e($selected_id ? 'Edit Store' : 'Create New Store'); ?>

                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="<?php if($selected_id): ?> Update <?php else: ?> Store <?php endif; ?>">
                        <div class="row g-3">
                            <!-- Store Name -->
                            <div class="col-md-6">
                                <label class="form-label">Store Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter store name">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" wire:model="email"
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="store@example.com">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" wire:model="phone"
                                    class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="+1 (555) 000-0000">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Initial Balance -->
                            <div class="col-md-6">
                                <label class="form-label">Initial Balance <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><?php echo e(config('app.currency_symbol', 'GHS')); ?></span>
                                    <input type="number" wire:model="initial_balance"
                                        class="form-control <?php $__errorArgs = ['initial_balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="0.00" step="0.01" min="0">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['initial_balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea wire:model="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2"
                                    placeholder="Enter store address"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Logo -->
                            <div class="col-12">
                                <label class="form-label">Store Logo</label>
                                <!--[if BLOCK]><![endif]--><?php if($currentLogo): ?>
                                    <div class="mb-3">
                                        <img src="<?php echo e(asset('storage/' . $currentLogo)); ?>" alt="Logo"
                                            class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <input type="file" wire:model="logo_path"
                                    class="form-control <?php $__errorArgs = ['logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                                <small class="text-muted">Max 2MB. Recommended: 200x200px</small>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['logo_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Active Status -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" wire:model="is_active" class="form-check-input"
                                        id="isActive">
                                    <label class="form-check-label" for="isActive">
                                        Store is Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Cancel</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="<?php if($selected_id): ?> Update <?php else: ?> Store <?php endif; ?>">
                        <?php echo e($selected_id ? 'Update Store' : 'Create Store'); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <!--[if BLOCK]><![endif]--><?php if($isEditMode): ?>
        <div class="modal-backdrop fade show"></div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-store.blade.php ENDPATH**/ ?>
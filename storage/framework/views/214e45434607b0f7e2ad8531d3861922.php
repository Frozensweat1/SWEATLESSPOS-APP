<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0"><?php echo e($componentName); ?> | <?php echo e($pageTitle); ?></h2>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-cash-register me-2"></i>Current Session
                </h6>
            </div>
            <div class="card-body">
                <!--[if BLOCK]><![endif]--><?php if($openSession): ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="small text-muted">Opened At</div>
                            <div class="fw-semibold"><?php echo e($openSession->opened_at->format('Y-m-d H:i')); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Opening Balance</div>
                            <div class="fw-semibold">Ghs <?php echo e(number_format($openSession->opening_balance, 2)); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Sales</div>
                            <div class="fw-semibold">
                                Ghs <?php echo e(number_format($liveSummary['cash_sales'] ?? 0, 2)); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Refunds</div>
                            <div class="fw-semibold">
                                Ghs <?php echo e(number_format($liveSummary['cash_refunds'] ?? 0, 2)); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Expenses</div>
                            <div class="fw-semibold">
                                Ghs <?php echo e(number_format($liveSummary['cash_expenses'] ?? 0, 2)); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Expected Cash</div>
                            <div class="fw-semibold">
                                Ghs <?php echo e(number_format($liveSummary['expected_cash'] ?? 0, 2)); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Discrepancy (Preview)</div>
                            <?php
                                $preview = $liveSummary['discrepancy'] ?? null;
                            ?>
                            <div
                                class="fw-semibold <?php echo e($preview > 0 ? 'text-success' : ($preview < 0 ? 'text-danger' : '')); ?>">
                                <!--[if BLOCK]><![endif]--><?php if($preview === null): ?>
                                    —
                                <?php else: ?>
                                    Ghs <?php echo e(number_format($preview, 2)); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Closing Balance</label>
                            <input type="number" min="0" step="0.01" class="form-control"
                                wire:model="closing_balance" placeholder="0.00">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['closing_balance'];
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
                        <div class="col-md-8">
                            <label class="form-label">Closing Note</label>
                            <input type="text" class="form-control" wire:model="closing_note"
                                placeholder="Optional note">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['closing_note'];
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
                        <button class="btn btn-falcon-danger btn-sm d-flex align-items-center" type="button"
                            wire:click="closeDrawer" wire:loading.attr="disabled">
                            <span class="fas fa-lock" data-fa-transform="shrink-3 down-2"></span>
                            <span class="ms-1">Close Drawer</span>
                            <span wire:loading wire:target="closeDrawer"
                                class="spinner-border spinner-border-sm ms-2" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Opening Balance</label>
                            <input type="number" min="0" step="0.01" class="form-control"
                                wire:model="opening_balance" placeholder="0.00">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['opening_balance'];
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
                        <div class="col-md-8">
                            <label class="form-label">Opening Note</label>
                            <input type="text" class="form-control" wire:model="opening_note"
                                placeholder="Optional note">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['opening_note'];
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
                            wire:click="openDrawer" wire:loading.attr="disabled">
                            <span class="fas fa-unlock" data-fa-transform="shrink-3 down-2"></span>
                            <span class="ms-1">Open Drawer</span>
                            <span wire:loading wire:target="openDrawer"
                                class="spinner-border spinner-border-sm ms-2" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search session ID"
                            wire:model.live="search">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="statusFilter">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="endDate">
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
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Session</th>
                                <th class="text-nowrap">User</th>
                                <th class="text-nowrap">Opened</th>
                                <th class="text-nowrap">Closed</th>
                                <th class="text-nowrap">Opening</th>
                                <th class="text-nowrap">Expected</th>
                                <th class="text-nowrap">Expenses</th>
                                <th class="text-nowrap">Closing</th>
                                <th class="text-nowrap">Discrepancy</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-nowrap">#<?php echo e($session->id); ?></td>
                                    <td class="text-nowrap"><?php echo e($session->user?->name ?? 'N/A'); ?></td>
                                    <td class="text-nowrap">
                                        <?php echo e($session->opened_at?->format('Y-m-d H:i') ?? 'N/A'); ?>

                                    </td>
                                    <td class="text-nowrap">
                                        <?php echo e($session->closed_at?->format('Y-m-d H:i') ?? '—'); ?>

                                    </td>
                                    <td class="text-nowrap">Ghs <?php echo e(number_format($session->opening_balance, 2)); ?></td>
                                    <td class="text-nowrap">Ghs <?php echo e(number_format($session->expected_cash, 2)); ?></td>
                                    <td class="text-nowrap">Ghs <?php echo e(number_format($session->cash_expenses, 2)); ?></td>
                                    <td class="text-nowrap">
                                        <?php echo e($session->closing_balance !== null ? 'Ghs ' . number_format($session->closing_balance, 2) : '—'); ?>

                                    </td>
                                    <td
                                        class="text-nowrap <?php echo e($session->discrepancy > 0 ? 'text-success' : ($session->discrepancy < 0 ? 'text-danger' : '')); ?>">
                                        <?php echo e($session->status === 'closed' ? 'Ghs ' . number_format($session->discrepancy, 2) : '—'); ?>

                                    </td>
                                    <td class="text-nowrap">
                                        <span class="badge bg-<?php echo e($session->status === 'open' ? 'warning' : 'success'); ?>">
                                            <?php echo e(ucfirst($session->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-info text-center mb-0">No sessions found.</div>
                                    </td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($sessions->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/manage-cash-drawer.blade.php ENDPATH**/ ?>
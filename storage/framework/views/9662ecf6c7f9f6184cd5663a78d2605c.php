<div>
    
    <div class="container">
        <!-- Filter Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Financial Report Filters</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="startDate" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-4">
                        <label for="endDate">End Date</label>
                        <input type="date" id="endDate" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-4">
                        <label for="userId">User</label>
                        <select id="userId" class="form-select" wire:model.live="userId">
                            <option value="">All Users</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- Results Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="card-text">Ghs <?php echo e(number_format($totalSales, 2)); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenditures</h5>
                        <p class="card-text">Ghs <?php echo e(number_format($totalExpenditures, 2)); ?></p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Inventory Value</h5>
                    <p class="card-text">GH₵ <?php echo e(number_format($totalInventoryValue, 2)); ?></p>
                </div>
            </div>
        </div>
        </div>

        <!-- Sales and Expenditures Table -->
        <div class="card">
            <div class="card-header">
                <h5>Sales and Expenditures Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($sale->id); ?></td>
                                    <td>Sale</td>
                                    <td>Ghs <?php echo e(number_format($sale->total_amount, 2)); ?></td>
                                    <td>Sale Transaction</td>
                                    <td><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                                    <td><?php echo e($sale->user->name ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $expenditures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expenditure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($expenditure->id); ?></td>
                                    <td>Expenditure</td>
                                    <td>Ghs <?php echo e(number_format($expenditure->amount, 2)); ?></td>
                                    <td><?php echo e($expenditure->description); ?></td>
                                    <td><?php echo e($expenditure->expenditure_date->format('Y-m-d')); ?></td>
                                    <td><?php echo e($expenditure->user->name ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                <!-- Single Pagination for Sales and Expenditures -->
                <div class="d-flex justify-content-between mt-3">
                    <?php echo e($sales->links()); ?> <!-- This handles the sales pagination -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/financial-report.blade.php ENDPATH**/ ?>
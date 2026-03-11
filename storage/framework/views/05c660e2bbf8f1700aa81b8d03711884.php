<div>
    
    <div class="container py-4">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Sales Report</h4>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-3">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select id="paymentMethod" class="form-select" wire:model.live="paymentMethod">
                            <option value="">All</option>
                            <option value="cash">Cash</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="mobile_money">Mobile Payment</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchTerm" class="form-label">Search Customer</label>
                        <input type="text" id="searchTerm" class="form-control" placeholder="Customer Name..."
                            wire:model.live="searchTerm">
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap" scope="col">Sale ID</th>
                                <th class="text-nowrap" scope="col">Customer</th>
                                <th class="text-nowrap" scope="col">Product</th>
                                <th class="text-nowrap" scope="col">Quantity</th>
                                <th class="text-nowrap" scope="col">Price per Unit</th>
                                <th class="text-nowrap" scope="col">Total</th>
                                <th class="text-nowrap" scope="col">Payment Method</th>
                                <th class="text-nowrap" scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $sale->saleDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-nowrap"><?php echo e($sale->id); ?></td>
                                        <td class="text-nowrap"><?php echo e($sale->customer ? $sale->customer->name : 'Guest'); ?>

                                        </td>
                                        <td class="text-nowrap"><?php echo e($detail->product->name); ?></td>
                                        <td class="text-nowrap"><?php echo e($detail->quantity); ?></td>
                                        <td class="text-nowrap">Ghs <?php echo e($detail->price_per_unit); ?></td>
                                        <td class="text-nowrap">Ghs <?php echo e($detail->subtotal); ?></td>
                                        <td class="text-nowrap"><?php echo e($sale->payment_method); ?></td>
                                        <td class="text-nowrap"><?php echo e($sale->created_at->format('Y-m-d')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination and Export -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <?php echo e($sales->links()); ?>

                    <div class="d-flex">
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
                                    wire:target="export('xlsx')">ing...</span> XLSX</span>
                            <span wire:loading wire:target="export('xlsx')"
                                class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/sale-report.blade.php ENDPATH**/ ?>
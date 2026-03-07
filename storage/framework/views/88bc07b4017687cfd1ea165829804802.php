<div>
    

    <div class="card">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-md-auto">
                    <h5 class="mb-3 mb-md-0">Point of Sale(POS)</h5>
                </div>

            </div>
        </div>
        <div class="p-2 card-body">
            <div class="mb-3">
                <label class="form-label" for="basic-form-name">Barcode</label>
                <input class="form-control form-control-sm" type="text" wire:model="barcode"
                    wire:keydown.enter="addProductByBarcode" placeholder="Scan Barcode" autocomplete="off" />
            </div>
            <div x-data="{ selectedProductId: <?php if ((object) ('selectedProductId') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedProductId'->value()); ?>')<?php echo e('selectedProductId'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedProductId'); ?>')<?php endif; ?> }">
                <!-- Product Selection Dropdown -->
                <div class="mb-3">
                    <label for="product" class="form-label">Select Product(If barcode is out of service!)</label>
                    <select wire:ignore x-model="selectedProductId" wire:change="addProductToCart"
                        class="form-select js-choice" x-init="new Choices($el);
                        $el.addEventListener('change', function(event) {
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('selectedProductId', event.target.value);
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').call('addProductToCart');
                        });">
                        <option value="">Select Product</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?> -
                                Ghs<?php echo e(number_format($product->price, 2)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
            </div>
            <div class="table-responsive scrollbar">
                <table class="table mb-0 overflow-hidden table-sm table-striped fs--1">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Toatal</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($item['name']); ?></td>
                                <td><?php echo e(number_format($item['price_per_unit'], 2)); ?></td>
                                <td>
                                    <input type="number" wire:model="cart.<?php echo e($index); ?>.quantity"
                                        wire:change="updateQuantity(<?php echo e($index); ?>, $event.target.value)" />
                                </td>
                                <td><?php echo e(number_format($item['total'], 2)); ?></td>
                                <td class="text-end">
                                    <div>
                                        <button
                                            wire:click="removeProductFromCart(<?php echo e($index); ?>)"class="p-0 btn ms-2"
                                            type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                            <span class="text-500 fas fa-trash-alt"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
            <div x-data="{ selectedCustomerId: <?php if ((object) ('customer_id') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('customer_id'->value()); ?>')<?php echo e('customer_id'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('customer_id'); ?>')<?php endif; ?> }">
                <!-- Customer Selection Dropdown -->
                <div class="mt-3" wire:ignore>
                    <label for="customer" class="form-label">Select Customer (If customer is registered!)</label>
                    <select x-model="selectedCustomerId" class="form-select js-choice" id="customer"
                        x-init="new Choices($el);
                        $el.addEventListener('change', function(event) {
                            selectedCustomerId = event.target.value;
                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('customer_id', event.target.value, true); // Update with debounce to prevent immediate re-render
                        });" size="1" name="customer">
                        <option value="">Select Customer</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
            </div>

        </div>
        <div class="card-footer bg-light d-flex justify-content-between">

            <div class="col-md-auto">
                <!-- Total Amount -->
                <h3>Total: <?php echo e(number_format($total_amount, 2)); ?></h3>
            </div>
            <div class="col-md-auto">
                <!-- Payment Method and Checkout -->
                <select class="form-control form-control-sm" wire:model="payment_method">
                    <option value="">Select Payment Method</option>
                    <optgroup label="Immediate Payment">
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="mobile_money">Mobile Money</option>
                    </optgroup>
                    <optgroup label="Credit Sales">
                        <option value="credit">Credit/Invoice</option>
                    </optgroup>
                </select>
            </div>

            <div class="col-md-auto">
                <button type="button" wire:click="processSale"
                    class="btn btn-sm btn-outline-primary rounded-pill me-1 d-flex align-items-center float-end"
                    wire:loading.attr="disabled" wire:loading.class="disabled"
                    wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                    <!-- Spinner -->
                    <span wire:loading wire:target="processSale" class="spinner-border spinner-border-sm me-2"
                        role="status" aria-hidden="true"></span>

                    <!-- Icon for smaller screens -->
                    <span class="d-none d-md-inline"><i class="fas fa-shopping-cart me-1"></i>
                        <span wire:loading.remove wire:target="processSale">
                            Complete Sale
                        </span>
                    </span>
                    <span wire:loading wire:target="processSale"> Completing Sale...</span>
                    <span class="d-md-none">
                        <i class="fas fa-shopping-cart me-1"></i>
                    </span>
                </button>
            </div>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($showReceipt): ?>
            <?php echo $__env->make('livewire.modals.receipt-modal-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- Receipt Header -->
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #333; padding-bottom: 15px;">
                <img src="<?php echo e($settings->LogoUrl ?? asset('assets/img/logo.png')); ?>" alt="Company Logo"
                    style="height: 50px; margin-bottom: 10px;">
                <h4 style="margin: 5px 0; font-weight: bold;"><?php echo e($settings->site_name ?? 'Sweatless POS'); ?></h4>
                <p style="margin: 2px 0; font-size: 11px;"><?php echo e($settings->receipt_header ?? 'In God We Trust!'); ?></p>
                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Company & Transaction Info -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <p style="margin: 3px 0;"><strong>Receipt #:</strong> <?php echo e($currentSale->id ?? 'N/A'); ?></p>
                <p style="margin: 3px 0;"><strong>Date:</strong> <?php echo e(now()->format('M d, Y')); ?></p>
                <p style="margin: 3px 0;"><strong>Time:</strong> <?php echo e(now()->format('H:i:s')); ?></p>
                <p style="margin: 3px 0;"><strong>Cashier:</strong> <?php echo e(auth()->user()->name); ?></p>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Company Details -->
            <div style="margin-bottom: 15px; font-size: 10px;">
                <p style="margin: 2px 0;"><strong><?php echo e($settings->site_name ?? 'Sweatless POS'); ?></strong></p>
                <p style="margin: 2px 0;"><?php echo e($settings->address ?? ''); ?></p>
                <p style="margin: 2px 0;">Tel: <?php echo e($settings->contact_phone ?? ''); ?></p>
                <p style="margin: 2px 0;">Email: <?php echo e($settings->contact_email ?? ''); ?></p>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Customer Information -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <p style="margin: 3px 0;"><strong>Customer:</strong></p>
                <!--[if BLOCK]><![endif]--><?php if($buying_customer): ?>
                    <p style="margin: 2px 0;">Name: <?php echo e($buying_customer->name); ?></p>
                    <p style="margin: 2px 0;">Phone: <?php echo e($buying_customer->phone ?? 'N/A'); ?></p>
                <?php else: ?>
                    <p style="margin: 2px 0;">Walk-in Customer</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Sales Barcode -->
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="data:image/png;base64,<?php echo e($saleBarcode); ?>" alt="Sale Barcode" style="height: 40px;">
                <p style="margin: 5px 0; font-size: 10px; font-weight: bold;"><?php echo e($currentSale->id ?? 'N/A'); ?></p>
            </div>

            <!-- Items Table -->
            <div style="margin-bottom: 15px;">
                <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px dashed #999;">
                            <th style="text-align: left; padding: 3px 0;">Item</th>
                            <th style="text-align: center; padding: 3px 0;">Qty</th>
                            <th style="text-align: right; padding: 3px 0;">Price</th>
                            <th style="text-align: right; padding: 3px 0;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="text-align: left; padding: 3px 0; word-wrap: break-word;">
                                    <?php echo e($item['name']); ?></td>
                                <td style="text-align: center; padding: 3px 0;"><?php echo e($item['quantity']); ?></td>
                                <td style="text-align: right; padding: 3px 0;">
                                    GHS<?php echo e(number_format($item['price_per_unit'], 2)); ?></td>
                                <td style="text-align: right; padding: 3px 0;">
                                    GHS<?php echo e(number_format($item['total'], 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Payment Summary -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <span><strong>Subtotal:</strong></span>
                    <span>GHS<?php echo e(number_format($total_amount, 2)); ?></span>
                </div>
                <div
                    style="display: flex; justify-content: space-between; margin: 5px 0; font-weight: bold; border-top: 1px dashed #999; border-bottom: 2px solid #333; padding: 8px 0;">
                    <span>TOTAL:</span>
                    <span>GHS<?php echo e(number_format($total_amount, 2)); ?></span>
                </div>
            </div>

            <!-- Payment Details -->
            <div
                style="margin-bottom: 15px; font-size: 10px; background-color: #f5f5f5; padding: 8px; border-radius: 4px;">
                <p style="margin: 3px 0;"><strong>Payment Method:</strong>
                    <?php
                        $methods = [
                            'cash' => 'Cash',
                            'credit_card' => 'Credit Card',
                            'mobile_money' => 'Mobile Money',
                            'credit' => 'Credit/Invoice',
                        ];
                    ?>
                    <?php echo e($methods[$payment_method] ?? ucfirst($payment_method)); ?>

                </p>
                <p style="margin: 3px 0; color: <?php echo e($currentSale->payment_status === 'paid' ? 'green' : 'orange'); ?>;">
                    <strong>Status:</strong>
                    <span style="text-transform: uppercase; font-weight: bold;">
                        <!--[if BLOCK]><![endif]--><?php if($currentSale->payment_status === 'paid'): ?>
                            ✓ PAID
                        <?php elseif($currentSale->payment_status === 'pending'): ?>
                            ⏳ PENDING
                        <?php else: ?>
                            ⚠ PARTIAL
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                </p>
                <!--[if BLOCK]><![endif]--><?php if($currentSale->payment_status !== 'paid'): ?>
                    <p style="margin: 3px 0;"><strong>Amount Paid:</strong>
                        GHS<?php echo e(number_format($currentSale->amount_paid ?? 0, 2)); ?></p>
                    <p style="margin: 3px 0; color: red;"><strong>Amount Due:</strong>
                        GHS<?php echo e(number_format($currentSale->amount_due ?? 0, 2)); ?></p>
                    <!--[if BLOCK]><![endif]--><?php if($currentSale->due_date): ?>
                        <p style="margin: 3px 0;"><strong>Due Date:</strong>
                            <?php echo e(\Carbon\Carbon::parse($currentSale->due_date)->format('M d, Y')); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Footer Message -->
            <div
                style="text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #999; font-size: 11px;">
                <p style="margin: 5px 0; font-weight: bold;">
                    <?php echo e($settings->receipt_footer ?? 'Thank you for your purchase!'); ?></p>
                <p style="margin: 5px 0; font-size: 10px; color: #666;"><?php echo e($settings->site_name ?? 'Sweatless POS'); ?>

                    - Point of Sale System</p>
            </div>

            <?php echo $__env->make('livewire.modals.receipt-modal-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Recent Sales Section -->
        <div class="mt-4 card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Sales (Last 15)</h5>
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-sm table-hover">
                        <thead class="bg-200">
                            <tr>
                                <th class="text-nowrap">Sale ID</th>
                                <th class="text-nowrap">Customer</th>
                                <th class="text-nowrap">Amount</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Payment Method</th>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><strong>#<?php echo e($sale->id); ?></strong></td>
                                    <td><?php echo e($sale->customer?->name ?? 'Walk-in Customer'); ?></td>
                                    <td>GHS<?php echo e(number_format($sale->total_amount, 2)); ?></td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($sale->payment_status === 'paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php elseif($sale->payment_status === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Partial</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td><?php echo e(ucfirst(str_replace('_', ' ', $sale->payment_method))); ?></td>
                                    <td><?php echo e($sale->created_at->format('M d, Y H:i')); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            wire:click="viewReceipt(<?php echo e($sale->id); ?>)" title="View Receipt">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="py-3 text-center text-muted">No sales yet today</td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View Receipt Modal -->
        <!--[if BLOCK]><![endif]--><?php if($showViewReceipt && $viewingSale): ?>
            <div class="modal fade show" id="viewReceiptModal" data-bs-keyboard="false" data-bs-backdrop="static"
                style="display: block;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="text-white modal-header bg-info">
                            <h5 class="modal-title">
                                <i class="fas fa-receipt me-2"></i>Sale Receipt #<?php echo e($viewingSale->id); ?>

                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeViewReceipt"></button>
                        </div>
                        <div class="modal-body" id="viewReceipt"
                            style="font-family: 'Courier New', monospace; font-size: 12px;">

                            <!-- Receipt Header -->
                            <div
                                style="text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #333; padding-bottom: 15px;">
                                <img src="<?php echo e($settings->LogoUrl ?? asset('assets/img/logo.png')); ?>"
                                    alt="Company Logo" style="height: 50px; margin-bottom: 10px;">
                                <h4 style="margin: 5px 0; font-weight: bold;">
                                    <?php echo e($settings->site_name ?? 'Sweatless POS'); ?></h4>
                                <p style="margin: 2px 0; font-size: 11px;">
                                    <?php echo e($settings->receipt_header ?? 'In God We Trust!'); ?></p>
                            </div>

                            <!-- Transaction Info -->
                            <div style="margin-bottom: 15px; font-size: 11px;">
                                <p style="margin: 3px 0;"><strong>Receipt #:</strong> <?php echo e($viewingSale->id); ?></p>
                                <p style="margin: 3px 0;"><strong>Date:</strong>
                                    <?php echo e($viewingSale->created_at->format('M d, Y')); ?></p>
                                <p style="margin: 3px 0;"><strong>Time:</strong>
                                    <?php echo e($viewingSale->created_at->format('H:i:s')); ?></p>
                                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
                            </div>

                            <!-- Shop Contact Details & Location -->
                            <div style="margin-bottom: 15px; font-size: 10px;">
                                <p style="margin: 2px 0;">
                                    <strong><?php echo e($settings->site_name ?? 'Sweatless POS'); ?></strong></p>
                                <p style="margin: 2px 0;"><?php echo e($settings->address ?? ''); ?></p>
                                <p style="margin: 2px 0;">Tel: <?php echo e($settings->contact_phone ?? ''); ?></p>
                                <p style="margin: 2px 0;">Email: <?php echo e($settings->contact_email ?? ''); ?></p>
                                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
                            </div>

                            <!-- Customer Info -->
                            <div style="margin-bottom: 15px; font-size: 11px;">
                                <p style="margin: 3px 0;"><strong>Customer:</strong></p>
                                <!--[if BLOCK]><![endif]--><?php if($viewingCustomer): ?>
                                    <p style="margin: 2px 0;"><?php echo e($viewingCustomer->name); ?></p>
                                    <p style="margin: 2px 0;"><?php echo e($viewingCustomer->phone ?? ''); ?></p>
                                <?php else: ?>
                                    <p style="margin: 2px 0;">Walk-in Customer</p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
                            </div>

                            <!-- Barcode -->
                            <div style="text-align: center; margin-bottom: 15px;">
                                <img src="data:image/png;base64,<?php echo e($viewingSaleBarcode); ?>" alt="Sale Barcode"
                                    style="height: 40px;">
                            </div>

                            <!-- Items Table -->
                            <div style="margin-bottom: 15px;">
                                <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
                                    <thead>
                                        <tr style="border-bottom: 1px dashed #999;">
                                            <th style="text-align: left; padding: 3px 0;">Item</th>
                                            <th style="text-align: center; padding: 3px 0;">Qty</th>
                                            <th style="text-align: right; padding: 3px 0;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $viewingSale->saleDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td style="padding: 3px 0;">
                                                    <!--[if BLOCK]><![endif]--><?php if($detail->product): ?>
                                                        <?php echo e($detail->product->name); ?>

                                                    <?php else: ?>
                                                        <span style="color: #dc3545;">
                                                            <i
                                                                class="fas fa-trash me-1"></i><?php echo e($detail->product_name ?? 'Deleted Product'); ?>

                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </td>
                                                <td style="text-align: center; padding: 3px 0;">
                                                    <?php echo e($detail->quantity); ?></td>
                                                <td style="text-align: right; padding: 3px 0;">
                                                    GHS<?php echo e(number_format($detail->subtotal, 2)); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </tbody>
                                </table>
                                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
                            </div>

                            <!-- Total -->
                            <div style="margin-bottom: 15px; font-size: 12px; font-weight: bold;">
                                <div
                                    style="display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding: 8px 0;">
                                    <span>TOTAL:</span>
                                    <span>GHS<?php echo e(number_format($viewingSale->total_amount, 2)); ?></span>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div
                                style="margin-bottom: 15px; font-size: 10px; background-color: #f5f5f5; padding: 8px; border-radius: 4px;">
                                <p style="margin: 3px 0;"><strong>Payment Method:</strong>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $viewingSale->payment_method))); ?></p>
                                <p style="margin: 3px 0;"><strong>Status:</strong>
                                    <!--[if BLOCK]><![endif]--><?php if($viewingSale->payment_status === 'paid'): ?>
                                        <span style="color: green; font-weight: bold;">✓ PAID</span>
                                    <?php elseif($viewingSale->payment_status === 'pending'): ?>
                                        <span style="color: orange; font-weight: bold;">⏳ PENDING</span>
                                    <?php else: ?>
                                        <span style="color: blue; font-weight: bold;">⚠ PARTIAL</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </p>
                            </div>

                            <div
                                style="text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #999; font-size: 11px;">
                                <p style="margin: 5px 0;">
                                    <?php echo e($settings->receipt_footer ?? 'Thank you for your purchase!'); ?></p>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button class="btn btn-secondary" type="button" wire:click="closeViewReceipt">
                                <i class="fas fa-times me-2"></i>Close
                            </button>
                            <button class="btn btn-info" type="button" onclick="printViewReceipt()">
                                <i class="fas fa-print me-2"></i>Print
                            </button>
                            <button class="btn btn-success" type="button" onclick="downloadViewReceipt()">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php $__env->startPush('styles'); ?>
            <link href="<?php echo e(asset('vendors/choices/choices.min.css')); ?>" rel="stylesheet" />
            <style>
                @media print {
                    body * {
                        visibility: hidden;
                    }

                    #receipt,
                    #receipt * {
                        visibility: visible;
                    }

                    #receipt {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                    }

                    .modal-header,
                    .modal-footer {
                        display: none !important;
                    }
                }
            </style>
        <?php $__env->stopPush(); ?>
        <?php if (! $__env->hasRenderedOnce('618cb27d-fb6a-471b-bc34-5016f95f3a2a')): $__env->markAsRenderedOnce('618cb27d-fb6a-471b-bc34-5016f95f3a2a'); ?>
            <?php $__env->startPush('js'); ?>
                <!-- Choice.js -->
                <script src="<?php echo e(asset('vendors/choices/choices.min.js')); ?>"></script>
                <!-- HTML2PDF for receipt download -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <?php $__env->stopPush(); ?>
        <?php endif; ?>

            <?php
        $__scriptKey = '2631755892-0';
        ob_start();
    ?>
            <script>
                window.printReceipt = function() {
                    var receiptContent = document.getElementById('receipt');
                    if (!receiptContent) return;

                    var printWindow = window.open('', '', 'width=800,height=600');
                    if (!printWindow) return;

                    var htmlContent = '<!DOCTYPE html><html><head><title>Receipt</title><style>' +
                        'body { font-family: "Courier New", monospace; font-size: 12px; margin: 10px; padding: 0; } ' +
                        'table { width: 100%; border-collapse: collapse; } ' +
                        'th, td { padding: 3px; border: none; } ' +
                        '@media print { body { margin: 0; padding: 0; } }' +
                        '</style></head><body>' + receiptContent.innerHTML + '</body></html>';

                    printWindow.document.write(htmlContent);
                    printWindow.document.close();
                    printWindow.focus();

                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 250);
                };

                window.downloadReceipt = function() {
                    const element = document.getElementById('receipt');
                    if (!element || typeof html2pdf === 'undefined') return;

                    const opt = {
                        margin: 5,
                        filename: 'receipt_sales.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2
                        },
                        jsPDF: {
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        }
                    };

                    html2pdf().set(opt).from(element).save();
                };

                window.printViewReceipt = function() {
                    var receiptContent = document.getElementById('viewReceipt');
                    if (!receiptContent) return;

                    var printWindow = window.open('', '', 'width=800,height=600');
                    if (!printWindow) return;

                    var htmlContent = '<!DOCTYPE html><html><head><title>Receipt</title><style>' +
                        'body { font-family: "Courier New", monospace; font-size: 12px; margin: 10px; padding: 0; } ' +
                        'table { width: 100%; border-collapse: collapse; } ' +
                        'th, td { padding: 3px; border: none; } ' +
                        'img { max-width: 100%; height: auto; } ' +
                        '@media print { body { margin: 0; padding: 0; } }' +
                        '</style></head><body>' + receiptContent.innerHTML + '</body></html>';

                    printWindow.document.write(htmlContent);
                    printWindow.document.close();
                    printWindow.focus();

                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 250);
                };

                window.downloadViewReceipt = function() {
                    const element = document.getElementById('viewReceipt');
                    if (!element || typeof html2pdf === 'undefined') return;

                    const opt = {
                        margin: 5,
                        filename: 'receipt_sales.pdf',
                        image: {
                            type: 'jpeg',
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 2
                        },
                        jsPDF: {
                            orientation: 'portrait',
                            unit: 'mm',
                            format: 'a4'
                        }
                    };

                    html2pdf().set(opt).from(element).save();
                };
            </script>
            <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
    </div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/make-sales.blade.php ENDPATH**/ ?>
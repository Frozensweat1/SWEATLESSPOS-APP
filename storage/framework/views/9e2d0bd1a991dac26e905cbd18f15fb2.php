<div>
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Settings</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <!-- Site Name -->
                        <div class="col-md-6">
                            <label for="site_name" class="form-label">Store Name</label>
                            <input type="text" id="site_name"
                                class="form-control <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="site_name">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['site_name'];
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

                        <!-- Currency -->
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency</label>
                            <select id="currency" class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="currency">
                                <option value="">Select Currency</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GHS">GHS</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['currency'];
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

                        <!-- Currency Symbol -->
                        <div class="col-md-6">
                            <label for="currency_symbol" class="form-label">Currency Symbol</label>
                            <input type="text" id="currency_symbol"
                                class="form-control <?php $__errorArgs = ['currency_symbol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="currency_symbol">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['currency_symbol'];
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

                        <!-- Tax Rate -->
                        <div class="col-md-6">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" id="tax_rate"
                                class="form-control <?php $__errorArgs = ['tax_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="tax_rate"
                                step="0.01" min="0" max="100">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['tax_rate'];
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

                        <!-- Contact Email -->
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" id="contact_email"
                                class="form-control <?php $__errorArgs = ['contact_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="contact_email">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contact_email'];
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

                        <!-- Contact Phone -->
                        <div class="col-md-6">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" id="contact_phone"
                                class="form-control <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="contact_phone">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['contact_phone'];
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

                        <!-- Address -->
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="address"
                                rows="3"></textarea>
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

                        <!-- Receipt Header -->
                        <div class="col-md-6">
                            <label for="receipt_header" class="form-label">Receipt Header</label>
                            <input type="text" id="receipt_header"
                                class="form-control <?php $__errorArgs = ['receipt_header'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="receipt_header">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['receipt_header'];
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

                        <!-- Receipt Footer -->
                        <div class="col-md-6">
                            <label for="receipt_footer" class="form-label">Receipt Footer</label>
                            <input type="text" id="receipt_footer"
                                class="form-control <?php $__errorArgs = ['receipt_footer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="receipt_footer">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['receipt_footer'];
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

                        <!-- Invoice Prefix -->
                        <div class="col-md-6">
                            <label for="invoice_prefix" class="form-label">Invoice Prefix</label>
                            <input type="text" id="invoice_prefix"
                                class="form-control <?php $__errorArgs = ['invoice_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="invoice_prefix">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['invoice_prefix'];
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

                        <!-- Auto Print Receipt -->
                        <div class="col-md-6">
                            <label for="auto_print_receipt" class="form-label">Auto Print Receipt</label>
                            <select id="auto_print_receipt"
                                class="form-select <?php $__errorArgs = ['auto_print_receipt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="auto_print_receipt">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['auto_print_receipt'];
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

                        <!-- Theme Color -->
                        <div class="col-md-6">
                            <label for="theme_color" class="form-label">Theme Color</label>
                            <select id="theme_color" class="form-select <?php $__errorArgs = ['theme_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="theme_color">
                                <option value="">Select Theme</option>
                                <option value="default">Default</option>
                                <option value="dark">Dark</option>
                                <option value="light">Light</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['theme_color'];
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
                        <div class="col-md-6">
                            <label for="logo_new" class="form-label">Logo</label>
                            <input type="file" id="logo_new"
                                class="form-control <?php $__errorArgs = ['logo_new'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="logo_new"
                                accept="image/*">
                            <!--[if BLOCK]><![endif]--><?php if($existing_logo && !$logo_new): ?>
                                <img src="<?php echo e(asset('storage/' . $existing_logo)); ?>" alt="Current Logo"
                                    class="img-fluid mt-2" style="max-height: 100px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($logo_new): ?>
                                <img src="<?php echo e($logo_new->temporaryUrl()); ?>" alt="Logo Preview"
                                    class="img-thumbnail mt-2" style="max-height: 100px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['logo_new'];
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

                        <!-- Favicon -->
                        <div class="col-md-6">
                            <label for="favicon_new" class="form-label">Favicon</label>
                            <input type="file" id="favicon_new"
                                class="form-control <?php $__errorArgs = ['favicon_new'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="favicon_new" accept="image/*">
                            <!--[if BLOCK]><![endif]--><?php if($existing_favicon && !$favicon_new): ?>
                                <img src="<?php echo e(asset('storage/' . $existing_favicon)); ?>" alt="Current Favicon"
                                    class="img-fluid mt-2" style="max-height: 50px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($favicon_new): ?>
                                <img src="<?php echo e($favicon_new->temporaryUrl()); ?>" alt="Favicon Preview"
                                    class="img-fluid mt-2" style="max-height: 50px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['favicon_new'];
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

                        <!-- Background Image 1 -->
                        <div class="col-md-6">
                            <label for="background_img1_new" class="form-label">Background Image 1</label>
                            <input type="file" id="background_img1_new"
                                class="form-control <?php $__errorArgs = ['background_img1_new'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="background_img1_new" accept="image/*">
                            <!--[if BLOCK]><![endif]--><?php if($existing_background_img1 && !$background_img1_new): ?>
                                <img src="<?php echo e(asset('storage/' . $existing_background_img1)); ?>"
                                    alt="Current Background Image 1" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($background_img1_new): ?>
                                <img src="<?php echo e($background_img1_new->temporaryUrl()); ?>"
                                    alt="Background Image 1 Preview" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['background_img1_new'];
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

                        <!-- Background Image 2 -->
                        <div class="col-md-6">
                            <label for="background_img2_new" class="form-label">Background Image 2</label>
                            <input type="file" id="background_img2_new"
                                class="form-control <?php $__errorArgs = ['background_img2_new'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="background_img2_new" accept="image/*">
                            <!--[if BLOCK]><![endif]--><?php if($existing_background_img2 && !$background_img2_new): ?>
                                <img src="<?php echo e(asset('storage/' . $existing_background_img2)); ?>"
                                    alt="Current Background Image 2" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($background_img2_new): ?>
                                <img src="<?php echo e($background_img2_new->temporaryUrl()); ?>"
                                    alt="Background Image 2 Preview" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['background_img2_new'];
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

                        <!-- Enable Two-Factor Authentication -->
                        <div class="col-md-6">
                            <label for="enable_two_factor_auth" class="form-label">Enable Two-Factor
                                Authentication</label>
                            <select id="enable_two_factor_auth"
                                class="form-select <?php $__errorArgs = ['enable_two_factor_auth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="enable_two_factor_auth">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['enable_two_factor_auth'];
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

                        <!-- Require Password Change -->
                        <div class="col-md-6">
                            <label for="require_password_change" class="form-label">Require Password Change on First
                                Login</label>
                            <select id="require_password_change"
                                class="form-select <?php $__errorArgs = ['require_password_change'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="require_password_change">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['require_password_change'];
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

                        <!-- Track Inventory -->
                        <div class="col-md-6">
                            <label for="track_inventory" class="form-label">Track Inventory</label>
                            <select id="track_inventory"
                                class="form-select <?php $__errorArgs = ['track_inventory'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="track_inventory">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['track_inventory'];
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

                        <!-- Low Stock Threshold -->
                        <div class="col-md-6">
                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                            <input type="number" id="low_stock_threshold"
                                class="form-control <?php $__errorArgs = ['low_stock_threshold'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="low_stock_threshold" min="0">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['low_stock_threshold'];
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

                        <!-- Enable Expiry Tracking -->
                        <div class="col-md-6">
                            <label for="enable_expiry_tracking" class="form-label">Enable Expiry Tracking</label>
                            <select id="enable_expiry_tracking"
                                class="form-select <?php $__errorArgs = ['enable_expiry_tracking'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                wire:model="enable_expiry_tracking">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['enable_expiry_tracking'];
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

                        <!-- Submit Button -->
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>Save Settings</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/settings.blade.php ENDPATH**/ ?>
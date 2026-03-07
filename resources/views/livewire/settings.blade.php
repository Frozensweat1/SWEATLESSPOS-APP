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
                                class="form-control @error('site_name') is-invalid @enderror" wire:model="site_name">
                            @error('site_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency</label>
                            <select id="currency" class="form-select @error('currency') is-invalid @enderror"
                                wire:model="currency">
                                <option value="">Select Currency</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GHS">GHS</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Currency Symbol -->
                        <div class="col-md-6">
                            <label for="currency_symbol" class="form-label">Currency Symbol</label>
                            <input type="text" id="currency_symbol"
                                class="form-control @error('currency_symbol') is-invalid @enderror"
                                wire:model="currency_symbol">
                            @error('currency_symbol')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tax Rate -->
                        <div class="col-md-6">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" id="tax_rate"
                                class="form-control @error('tax_rate') is-invalid @enderror" wire:model="tax_rate"
                                step="0.01" min="0" max="100">
                            @error('tax_rate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="col-md-6">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" id="contact_email"
                                class="form-control @error('contact_email') is-invalid @enderror"
                                wire:model="contact_email">
                            @error('contact_email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Phone -->
                        <div class="col-md-6">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" id="contact_phone"
                                class="form-control @error('contact_phone') is-invalid @enderror"
                                wire:model="contact_phone">
                            @error('contact_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror" wire:model="address"
                                rows="3"></textarea>
                            @error('address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Receipt Header -->
                        <div class="col-md-6">
                            <label for="receipt_header" class="form-label">Receipt Header</label>
                            <input type="text" id="receipt_header"
                                class="form-control @error('receipt_header') is-invalid @enderror"
                                wire:model="receipt_header">
                            @error('receipt_header')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Receipt Footer -->
                        <div class="col-md-6">
                            <label for="receipt_footer" class="form-label">Receipt Footer</label>
                            <input type="text" id="receipt_footer"
                                class="form-control @error('receipt_footer') is-invalid @enderror"
                                wire:model="receipt_footer">
                            @error('receipt_footer')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invoice Prefix -->
                        <div class="col-md-6">
                            <label for="invoice_prefix" class="form-label">Invoice Prefix</label>
                            <input type="text" id="invoice_prefix"
                                class="form-control @error('invoice_prefix') is-invalid @enderror"
                                wire:model="invoice_prefix">
                            @error('invoice_prefix')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Auto Print Receipt -->
                        <div class="col-md-6">
                            <label for="auto_print_receipt" class="form-label">Auto Print Receipt</label>
                            <select id="auto_print_receipt"
                                class="form-select @error('auto_print_receipt') is-invalid @enderror"
                                wire:model="auto_print_receipt">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('auto_print_receipt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Theme Color -->
                        <div class="col-md-6">
                            <label for="theme_color" class="form-label">Theme Color</label>
                            <select id="theme_color" class="form-select @error('theme_color') is-invalid @enderror"
                                wire:model="theme_color">
                                <option value="">Select Theme</option>
                                <option value="default">Default</option>
                                <option value="dark">Dark</option>
                                <option value="light">Light</option>
                            </select>
                            @error('theme_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="col-md-6">
                            <label for="logo_new" class="form-label">Logo</label>
                            <input type="file" id="logo_new"
                                class="form-control @error('logo_new') is-invalid @enderror" wire:model="logo_new"
                                accept="image/*">
                            @if ($existing_logo && !$logo_new)
                                <img src="{{ asset('storage/' . $existing_logo) }}" alt="Current Logo"
                                    class="img-fluid mt-2" style="max-height: 100px;">
                            @endif
                            @if ($logo_new)
                                <img src="{{ $logo_new->temporaryUrl() }}" alt="Logo Preview"
                                    class="img-thumbnail mt-2" style="max-height: 100px;">
                            @endif
                            @error('logo_new')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Favicon -->
                        <div class="col-md-6">
                            <label for="favicon_new" class="form-label">Favicon</label>
                            <input type="file" id="favicon_new"
                                class="form-control @error('favicon_new') is-invalid @enderror"
                                wire:model="favicon_new" accept="image/*">
                            @if ($existing_favicon && !$favicon_new)
                                <img src="{{ asset('storage/' . $existing_favicon) }}" alt="Current Favicon"
                                    class="img-fluid mt-2" style="max-height: 50px;">
                            @endif
                            @if ($favicon_new)
                                <img src="{{ $favicon_new->temporaryUrl() }}" alt="Favicon Preview"
                                    class="img-fluid mt-2" style="max-height: 50px;">
                            @endif
                            @error('favicon_new')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Background Image 1 -->
                        <div class="col-md-6">
                            <label for="background_img1_new" class="form-label">Background Image 1</label>
                            <input type="file" id="background_img1_new"
                                class="form-control @error('background_img1_new') is-invalid @enderror"
                                wire:model="background_img1_new" accept="image/*">
                            @if ($existing_background_img1 && !$background_img1_new)
                                <img src="{{ asset('storage/' . $existing_background_img1) }}"
                                    alt="Current Background Image 1" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            @endif
                            @if ($background_img1_new)
                                <img src="{{ $background_img1_new->temporaryUrl() }}"
                                    alt="Background Image 1 Preview" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            @endif
                            @error('background_img1_new')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Background Image 2 -->
                        <div class="col-md-6">
                            <label for="background_img2_new" class="form-label">Background Image 2</label>
                            <input type="file" id="background_img2_new"
                                class="form-control @error('background_img2_new') is-invalid @enderror"
                                wire:model="background_img2_new" accept="image/*">
                            @if ($existing_background_img2 && !$background_img2_new)
                                <img src="{{ asset('storage/' . $existing_background_img2) }}"
                                    alt="Current Background Image 2" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            @endif
                            @if ($background_img2_new)
                                <img src="{{ $background_img2_new->temporaryUrl() }}"
                                    alt="Background Image 2 Preview" class="img-fluid mt-2"
                                    style="max-height: 150px;">
                            @endif
                            @error('background_img2_new')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Enable Two-Factor Authentication -->
                        <div class="col-md-6">
                            <label for="enable_two_factor_auth" class="form-label">Enable Two-Factor
                                Authentication</label>
                            <select id="enable_two_factor_auth"
                                class="form-select @error('enable_two_factor_auth') is-invalid @enderror"
                                wire:model="enable_two_factor_auth">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('enable_two_factor_auth')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Require Password Change -->
                        <div class="col-md-6">
                            <label for="require_password_change" class="form-label">Require Password Change on First
                                Login</label>
                            <select id="require_password_change"
                                class="form-select @error('require_password_change') is-invalid @enderror"
                                wire:model="require_password_change">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('require_password_change')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Track Inventory -->
                        <div class="col-md-6">
                            <label for="track_inventory" class="form-label">Track Inventory</label>
                            <select id="track_inventory"
                                class="form-select @error('track_inventory') is-invalid @enderror"
                                wire:model="track_inventory">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('track_inventory')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Low Stock Threshold -->
                        <div class="col-md-6">
                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                            <input type="number" id="low_stock_threshold"
                                class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                wire:model="low_stock_threshold" min="0">
                            @error('low_stock_threshold')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Enable Expiry Tracking -->
                        <div class="col-md-6">
                            <label for="enable_expiry_tracking" class="form-label">Enable Expiry Tracking</label>
                            <select id="enable_expiry_tracking"
                                class="form-select @error('enable_expiry_tracking') is-invalid @enderror"
                                wire:model="enable_expiry_tracking">
                                <option value="1">Enabled</option>
                                <option value="0">Disabled</option>
                            </select>
                            @error('enable_expiry_tracking')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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

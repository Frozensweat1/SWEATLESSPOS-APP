<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}

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
            <div x-data="{ selectedProductId: @entangle('selectedProductId') }">
                <!-- Product Selection Dropdown -->
                <div class="mb-3">
                    <label for="product" class="form-label">Select Product(If barcode is out of service!)</label>
                    <select wire:ignore x-model="selectedProductId" wire:change="addProductToCart"
                        class="form-select js-choice" x-init="new Choices($el);
                        $el.addEventListener('change', function(event) {
                            @this.set('selectedProductId', event.target.value);
                            @this.call('addProductToCart');
                        });">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} -
                                Ghs{{ number_format($product->price, 2) }}</option>
                        @endforeach
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
                            @if ($discountAllowed)
                                <th scope="col">Discount</th>
                            @endif
                            <th scope="col">Toatal</th>
                            <th class="text-end" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($cart as $index => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format($item['price_per_unit'], 2) }}</td>
                                <td>
                                    <input type="number" min="1" max="{{ $item['available_stock'] ?? '' }}"
                                        wire:model="cart.{{ $index }}.quantity"
                                        wire:change="updateQuantity({{ $index }}, $event.target.value)" />
                                </td>
                                @if ($discountAllowed)
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <select class="form-select form-select-sm"
                                                wire:model="cart.{{ $index }}.discount_type"
                                                wire:change="updateItemDiscount({{ $index }})">
                                                <option value="">None</option>
                                                <option value="percent">%</option>
                                                <option value="amount">Amount</option>
                                            </select>
                                            <input type="number" class="form-control form-control-sm" min="0"
                                                step="0.01" wire:model="cart.{{ $index }}.discount_value"
                                                wire:change="updateItemDiscount({{ $index }})" placeholder="0">
                                        </div>
                                        @if (!empty($item['discount_amount']))
                                            <div class="text-muted small mt-1">
                                                -{{ number_format($item['discount_amount'], 2) }}
                                            </div>
                                        @endif
                                    </td>
                                @endif
                                <td>{{ number_format($item['total'], 2) }}</td>
                                <td class="text-end">
                                    <div>
                                        <button
                                            wire:click="removeProductFromCart({{ $index }})"class="p-0 btn ms-2"
                                            type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Delete">
                                            <span class="text-500 fas fa-trash-alt"></span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div x-data="{ selectedCustomerId: @entangle('customer_id') }">
                <!-- Customer Selection Dropdown -->
                <div class="mt-3" wire:ignore>
                    <label for="customer" class="form-label">Select Customer (If customer is registered!)</label>
                    <select x-model="selectedCustomerId" class="form-select js-choice" id="customer"
                        x-init="new Choices($el);
                        $el.addEventListener('change', function(event) {
                            selectedCustomerId = event.target.value;
                            @this.set('customer_id', event.target.value, true); // Update with debounce to prevent immediate re-render
                        });" size="1" name="customer">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="card-footer bg-light">
            <div class="row g-2">
                <!-- Total Amount Section -->
                <div class="col-12 col-md-auto">
                    <div class="fw-semibold">Subtotal: {{ number_format($subtotal_amount, 2) }}</div>
                    @if ($discountAllowed)
                        <div class="text-muted small">Discounts: {{ number_format($total_discount, 2) }}</div>
                    @endif
                    <h3 class="mt-1">Total: {{ number_format($total_amount, 2) }}</h3>
                </div>

                <!-- Cart Discount Section -->
                @if ($discountAllowed)
                    <div class="col-12 col-md-auto">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" wire:model="cart_discount_type"
                                    wire:change="updateCartDiscount" style="flex: 1; min-width: 120px;">
                                    <option value="">Cart Discount</option>
                                    <option value="percent">%</option>
                                    <option value="amount">Amount</option>
                                </select>
                                <input type="number" class="form-control form-control-sm" min="0" step="0.01"
                                    wire:model="cart_discount_value" wire:change="updateCartDiscount" placeholder="0"
                                    style="flex: 1; min-width: 80px;">
                            </div>
                            @if ($cart_discount_amount > 0)
                                <div class="text-muted small">
                                    Cart discount: -{{ number_format($cart_discount_amount, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-12 col-md-auto d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary w-100"
                            wire:click="openDiscountAuthModal">
                            <i class="fas fa-user-shield me-1"></i>Authorize Discount
                        </button>
                    </div>
                @endif

                <!-- Payment Method Section -->
                <div class="col-12 col-md-auto">
                    <select class="form-select form-select-sm" wire:model="payment_method" style="min-width: 180px;">
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

                <!-- Complete Sale Button Section -->
                <div class="col-12 col-md-auto">
                    <button type="button" wire:click="processSale"
                        class="btn btn-sm btn-outline-primary rounded-pill w-100 d-flex align-items-center justify-content-center"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary"
                        style="min-width: 150px;">
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
        </div>
        @if ($showReceipt)
            @include('livewire.modals.receipt-modal-header')

            <!-- Receipt Header -->
            <div
                style="text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #333; padding-bottom: 15px;">
                <img src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}" alt="Company Logo"
                    style="height: 50px; margin-bottom: 10px;">
                <h4 style="margin: 5px 0; font-weight: bold;">{{ $settings->site_name ?? 'Sweatless POS' }}</h4>
                <p style="margin: 2px 0; font-size: 11px;">{{ $settings->receipt_header ?? 'In God We Trust!' }}</p>
                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Company & Transaction Info -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <p style="margin: 3px 0;"><strong>Receipt #:</strong> {{ $currentSale->id ?? 'N/A' }}</p>
                <p style="margin: 3px 0;"><strong>Date:</strong> {{ now()->format('M d, Y') }}</p>
                <p style="margin: 3px 0;"><strong>Time:</strong> {{ now()->format('H:i:s') }}</p>
                <p style="margin: 3px 0;"><strong>Cashier:</strong> {{ auth()->user()->name }}</p>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Company Details -->
            <div style="margin-bottom: 15px; font-size: 10px;">
                <p style="margin: 2px 0;"><strong>{{ $settings->site_name ?? 'Sweatless POS' }}</strong></p>
                <p style="margin: 2px 0;">{{ $settings->address ?? '' }}</p>
                <p style="margin: 2px 0;">Tel: {{ $settings->contact_phone ?? '' }}</p>
                <p style="margin: 2px 0;">Email: {{ $settings->contact_email ?? '' }}</p>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Customer Information -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <p style="margin: 3px 0;"><strong>Customer:</strong></p>
                @if ($buying_customer)
                    <p style="margin: 2px 0;">Name: {{ $buying_customer->name }}</p>
                    <p style="margin: 2px 0;">Phone: {{ $buying_customer->phone ?? 'N/A' }}</p>
                @else
                    <p style="margin: 2px 0;">Walk-in Customer</p>
                @endif
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Sales Barcode -->
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="data:image/png;base64,{{ $saleBarcode }}" alt="Sale Barcode" style="height: 40px;">
                <p style="margin: 5px 0; font-size: 10px; font-weight: bold;">{{ $currentSale->id ?? 'N/A' }}</p>
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
                        @foreach ($cart as $item)
                            <tr>
                                <td style="text-align: left; padding: 3px 0; word-wrap: break-word;">
                                    {{ $item['name'] }}</td>
                                <td style="text-align: center; padding: 3px 0;">{{ $item['quantity'] }}</td>
                                <td style="text-align: right; padding: 3px 0;">
                                    GHS{{ number_format($item['price_per_unit'], 2) }}</td>
                                <td style="text-align: right; padding: 3px 0;">
                                    GHS{{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr style="margin: 8px 0; border: none; border-top: 1px dashed #999;">
            </div>

            <!-- Payment Summary -->
            <div style="margin-bottom: 15px; font-size: 11px;">
                <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                    <span><strong>Subtotal:</strong></span>
                    <span>GHS{{ number_format($subtotal_amount, 2) }}</span>
                </div>
                @if ($total_discount > 0)
                    <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                        <span><strong>Discount:</strong></span>
                        <span>-GHS{{ number_format($total_discount, 2) }}</span>
                    </div>
                @endif
                <div
                    style="display: flex; justify-content: space-between; margin: 5px 0; font-weight: bold; border-top: 1px dashed #999; border-bottom: 2px solid #333; padding: 8px 0;">
                    <span>TOTAL:</span>
                    <span>GHS{{ number_format($total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Payment Details -->
            <div
                style="margin-bottom: 15px; font-size: 10px; background-color: #f5f5f5; padding: 8px; border-radius: 4px;">
                <p style="margin: 3px 0;"><strong>Payment Method:</strong>
                    @php
                        $methods = [
                            'cash' => 'Cash',
                            'credit_card' => 'Credit Card',
                            'mobile_money' => 'Mobile Money',
                            'credit' => 'Credit/Invoice',
                        ];
                    @endphp
                    {{ $methods[$payment_method] ?? ucfirst($payment_method) }}
                </p>
                <p style="margin: 3px 0; color: {{ $currentSale->payment_status === 'paid' ? 'green' : 'orange' }};">
                    <strong>Status:</strong>
                    <span style="text-transform: uppercase; font-weight: bold;">
                        @if ($currentSale->payment_status === 'paid')
                            ✓ PAID
                        @elseif ($currentSale->payment_status === 'pending')
                            ⏳ PENDING
                        @else
                            ⚠ PARTIAL
                        @endif
                    </span>
                </p>
                @if ($currentSale->payment_status !== 'paid')
                    <p style="margin: 3px 0;"><strong>Amount Paid:</strong>
                        GHS{{ number_format($currentSale->amount_paid ?? 0, 2) }}</p>
                    <p style="margin: 3px 0; color: red;"><strong>Amount Due:</strong>
                        GHS{{ number_format($currentSale->amount_due ?? 0, 2) }}</p>
                    @if ($currentSale->due_date)
                        <p style="margin: 3px 0;"><strong>Due Date:</strong>
                            {{ \Carbon\Carbon::parse($currentSale->due_date)->format('M d, Y') }}</p>
                    @endif
                @endif
            </div>

            <!-- Footer Message -->
            <div
                style="text-align: center; margin-top: 15px; padding-top: 10px; border-top: 1px dashed #999; font-size: 11px;">
                <p style="margin: 5px 0; font-weight: bold;">
                    {{ $settings->receipt_footer ?? 'Thank you for your purchase!' }}</p>
                <p style="margin: 5px 0; font-size: 10px; color: #666;">{{ $settings->site_name ?? 'Sweatless POS' }}
                    - Point of Sale System</p>
            </div>

            @include('livewire.modals.receipt-modal-footer')
        @endif

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
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td><strong>#{{ $sale->id }}</strong></td>
                                    <td>{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                                    <td>GHS{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @if ($sale->payment_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif ($sale->payment_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-info">Partial</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</td>
                                    <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            wire:click="viewReceipt({{ $sale->id }})" title="View Receipt">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-3 text-center text-muted">No sales yet today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- View Receipt Modal -->
        @if ($showViewReceipt && $viewingSale)
            <div class="modal fade show" id="viewReceiptModal" data-bs-keyboard="false" data-bs-backdrop="static"
                style="display: block;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="text-white modal-header bg-info">
                            <h5 class="modal-title">
                                <i class="fas fa-receipt me-2"></i>Sale Receipt #{{ $viewingSale->id }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeViewReceipt"></button>
                        </div>
                        <div class="modal-body" id="viewReceipt"
                            style="font-family: 'Courier New', monospace; font-size: 14px; color: #333;">

                            <!-- Receipt Header -->
                            <div
                                style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px;">
                                <img src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}"
                                    alt="Company Logo" style="height: 60px; margin-bottom: 10px;">
                                <h3 style="margin: 5px 0; font-weight: bold;">
                                    {{ $settings->site_name ?? 'Sweatless POS' }}</h3>
                                <p style="margin: 5px 0; font-size: 13px;">
                                    {{ $settings->receipt_header ?? 'In God We Trust!' }}</p>
                            </div>

                            <!-- Transaction Info -->
                            <div style="margin-bottom: 15px; font-size: 14px;">
                                <p style="margin: 5px 0;"><strong>Receipt No:</strong> {{ $viewingSale->id }}</p>
                                <p style="margin: 5px 0;"><strong>Date:</strong>
                                    {{ $viewingSale->created_at->format('M d, Y') }}</p>
                                <p style="margin: 5px 0;"><strong>Time:</strong>
                                    {{ $viewingSale->created_at->format('H:i:s') }}</p>
                                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #666;">
                            </div>

                            <!-- Shop Contact Details -->
                            <div style="margin-bottom: 15px; font-size: 13px;">
                                <p style="margin: 5px 0;">
                                    <strong>{{ $settings->site_name ?? 'Sweatless POS' }}</strong>
                                </p>
                                <p style="margin: 5px 0;">{{ $settings->address ?? '' }}</p>
                                <p style="margin: 5px 0;">Tel: {{ $settings->contact_phone ?? '' }}</p>
                                <p style="margin: 5px 0;">Email: {{ $settings->contact_email ?? '' }}</p>
                                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #666;">
                            </div>

                            <!-- Customer Info -->
                            <div style="margin-bottom: 15px; font-size: 14px;">
                                <p style="margin: 5px 0;"><strong>Customer:</strong></p>
                                @if ($viewingCustomer)
                                    <p style="margin: 3px 0;">{{ $viewingCustomer->name }}</p>
                                    <p style="margin: 3px 0;">{{ $viewingCustomer->phone ?? '' }}</p>
                                @else
                                    <p style="margin: 3px 0;">Walk-in Customer</p>
                                @endif
                                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #666;">
                            </div>

                            <!-- Barcode -->
                            <div style="text-align: center; margin-bottom: 20px;">
                                <img src="data:image/png;base64,{{ $viewingSaleBarcode }}" alt="Sale Barcode"
                                    style="height: 50px;">
                            </div>

                            <!-- Items Table -->
                            <div style="margin-bottom: 20px;">
                                <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid #333;">
                                            <th style="text-align: left; padding: 8px 0;">Item Description</th>
                                            <th style="text-align: center; padding: 8px 0;">Qty</th>
                                            <th style="text-align: right; padding: 8px 0;">Price</th>
                                            <th style="text-align: right; padding: 8px 0;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($viewingSale->saleDetails as $detail)
                                            <tr>
                                                <td style="padding: 8px 0; vertical-align: top;">
                                                    @if ($detail->product)
                                                        {{ $detail->product->name }}
                                                    @else
                                                        <span style="color: #dc3545;">
                                                            <i
                                                                class="fas fa-trash me-1"></i>{{ $detail->product_name ?? 'Deleted Product' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td style="text-align: center; padding: 8px 0;">
                                                    {{ $detail->quantity }}</td>
                                                <td style="text-align: right; padding: 8px 0;">
                                                    {{ number_format($detail->subtotal / $detail->quantity, 2) }}</td>
                                                <td style="text-align: right; padding: 8px 0;">
                                                    {{ number_format($detail->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr style="margin: 10px 0; border: none; border-top: 1px dashed #666;">
                            </div>

                            <!-- Totals -->
                            <div style="margin-bottom: 20px; font-size: 15px;">
                                @if (($viewingSale->total_discount ?? 0) > 0)
                                    <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                                        <span><strong>SUBTOTAL:</strong></span>
                                        <span>GHS{{ number_format($viewingSale->total_amount + $viewingSale->total_discount, 2) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                                        <span><strong>DISCOUNT:</strong></span>
                                        <span
                                            style="color: #28a745;">-GHS{{ number_format($viewingSale->total_discount, 2) }}</span>
                                    </div>
                                @endif
                                <div
                                    style="display: flex; justify-content: space-between; border-top: 3px solid #333; border-bottom: 3px solid #333; padding: 12px 0; margin-top: 8px;">
                                    <span style="font-size: 18px;"><strong>TOTAL</strong></span>
                                    <span
                                        style="font-size: 18px;"><strong>GHS{{ number_format($viewingSale->total_amount, 2) }}</strong></span>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div
                                style="margin-bottom: 20px; font-size: 14px; background-color: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #dee2e6;">
                                <p style="margin: 5px 0;"><strong>Payment Method:</strong>
                                    {{ ucwords(str_replace('_', ' ', $viewingSale->payment_method)) }}</p>
                                <p style="margin: 5px 0;">
                                    <strong>Payment Status:</strong>
                                    @if ($viewingSale->payment_status === 'paid')
                                        <span style="color: #28a745; font-weight: bold; font-size: 16px;">PAID</span>
                                    @elseif ($viewingSale->payment_status === 'pending')
                                        <span
                                            style="color: #ffc107; font-weight: bold; font-size: 16px;">PENDING</span>
                                    @else
                                        <span
                                            style="color: #17a2b8; font-weight: bold; font-size: 16px;">PARTIAL</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Footer -->
                            <div
                                style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #666; font-size: 14px;">
                                <p style="margin: 8px 0; font-weight: bold;">
                                    {{ $settings->receipt_footer ?? 'Thank you for your purchase!' }}</p>
                                <p style="margin: 8px 0; font-size: 12px; color: #666;">Please keep this receipt for
                                    your records</p>
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
        @endif

        <!-- Discount Authorization Modal -->
        @if ($showDiscountAuthModal)
            <div class="modal fade show" id="discountAuthModal" data-bs-keyboard="false" data-bs-backdrop="static"
                style="display: block;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-user-shield me-2"></i>Manager Authorization
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDiscountAuthModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Manager Email</label>
                                <input type="email" class="form-control" wire:model.defer="manager_email"
                                    placeholder="manager@example.com">
                                @error('manager_email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" wire:model.defer="manager_password">
                                @error('manager_password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-muted small">
                                Discounts are restricted. An admin or super-admin must authorize.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="closeDiscountAuthModal">Cancel</button>
                            <button type="button" class="btn btn-primary" wire:click="authorizeDiscount">
                                <i class="fas fa-check me-1"></i>Authorize
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif

        @push('styles')
            <link href="{{ asset('vendors/choices/choices.min.css') }}" rel="stylesheet" />
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
        @endpush
        @once
            @push('js')
                <!-- Choice.js -->
                <script src="{{ asset('vendors/choices/choices.min.js') }}"></script>
                <!-- HTML2PDF for receipt download -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            @endpush
        @endonce

        @script
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
        @endscript
    </div>

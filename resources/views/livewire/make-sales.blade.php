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
        <div class="card-body p-2">
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
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
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

                        @foreach ($cart as $index => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ number_format($item['price_per_unit'], 2) }}</td>
                                <td>
                                    <input type="number" wire:model="cart.{{ $index }}.quantity"
                                        wire:change="updateQuantity({{ $index }}, $event.target.value)" />
                                </td>
                                <td>{{ number_format($item['total'], 2) }}</td>
                                <td class="text-end">
                                    <div>
                                        <button
                                            wire:click="removeProductFromCart({{ $index }})"class="btn p-0 ms-2"
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
        <div class="card-footer bg-light d-flex justify-content-between">

            <div class="col-md-auto">
                <!-- Total Amount -->
                <h3>Total: {{ number_format($total_amount, 2) }}</h3>
            </div>
            <div class="col-md-auto">
                <!-- Payment Method and Checkout -->
                <select class="form-control form-control-sm" wire:model="payment_method">
                    <option selected value="cash">Cash</option>
                    <option value="credit_card">Card</option>
                    <option value="mobile_money">MoMo</option>
                </select>
            </div>

            <div class="col-md-auto">
                <button type="button" wire:click="processSale"
                    class="btn btn-sm btn-outline-primary rounded-pill me-1  d-flex align-items-center float-end"
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
        @if ($showReceipt)
            @include('livewire.modals.receipt-modal-header')

            <div class="text-center mb-3">
                <!-- Display Company Logo if available -->
                @if ($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Company Logo" class="img-fluid"
                        style="max-width: 150px;">
                @endif
                <h5>{{ $settings->site_name ?? 'Sweatless POS' }}</h5>
                <!-- Receipt Footer Text -->
                <p class="text-center">{{ $settings->receipt_header ?? 'In God We Trust!' }}</p>
            </div>

            <h6><b>Company Name:</b> {{ $settings->site_name }}</h6>
            <h6><b>Company Tel:</b> {{ $settings->contact_phone }}</h6>
            <h6><b>Email:</b> {{ $settings->contact_email }}</h6>
            <h6><b>Address:</b> {{ $settings->address }}</h6>
            <h6><b>Cashier:</b> {{ auth()->user()->name }}</h6>
            <!-- Customer Information -->
            @if ($buying_customer)
                <h6><b>Customer Name:</b> {{ $buying_customer->name }}</h6>
                <h6><b>Customer Phone:</b> {{ $buying_customer->phone }}</h6>
            @else
                <h6><b>Customer Name:</b> Walk-in Customer</h6>
            @endif

            <!-- Display Barcode -->
            <div class="text-center my-3">
                <img src="data:image/png;base64,{{ $saleBarcode }}" alt="Sale Barcode">
            </div>

            <p><strong>Date:</strong> {{ now()->format('Y-m-d H:i') }}</p>

            <!-- Cart Items -->
            <div class="table-responsive scrollbar">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>Ghs{{ number_format($item['price_per_unit'], 2) }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Ghs{{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <p><strong>Total: Ghs{{ number_format($total_amount, 2) }}</strong></p>
            <hr>

            <!-- Receipt Footer Text -->
            <p class="text-center">{{ $settings->receipt_footer ?? 'Thank you for your purchase!' }}</p>

            @include('livewire.modals.receipt-modal-footer')
        @endif

        @push('styles')
            <link href="{{ asset('vendors/choices/choices.min.css') }}" rel="stylesheet" />
        @endpush
        @push('js')
            <!-- Choice.js -->
            <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
            <script>
                function printReceipt() {
                    var receiptContent = document.getElementById('receipt').innerHTML;
                    var originalContent = document.body.innerHTML;

                    document.body.innerHTML = receiptContent; // Set body to only receipt content
                    window.print(); // Print the page
                    document.body.innerHTML = originalContent; // Restore original content
                    window.location.reload(); // Reload to reinitialize the page
                }
            </script>
        @endpush
    </div>

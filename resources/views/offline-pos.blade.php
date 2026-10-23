<x-app-layout>
    <div class="container-fluid" id="offline-pos" data-currency="{{ config('app.currency_symbol', 'GHS') }}">
        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <div>
                    <h5 class="mb-0">Offline POS</h5>
                    <div class="fs--1 text-600">Sell without a connection. Sales are saved on this device and sync automatically when you're back online.</div>
                </div>
                <div class="ms-auto d-flex flex-wrap align-items-center gap-2">
                    <span id="op-net" class="badge rounded-pill bg-success">Online</span>
                    <span id="op-pending" class="badge rounded-pill bg-warning text-dark">0 waiting to sync</span>
                    <button type="button" id="op-sync" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-sync me-1"></span> Sync now
                    </button>
                    <button type="button" id="op-refresh" class="btn btn-falcon-default btn-sm">
                        <span class="fas fa-box me-1"></span> Refresh products
                    </button>
                </div>
            </div>
            <div class="card-footer bg-light fs--1 text-600" id="op-catalog-info">Loading product catalog…</div>
        </div>

        <div id="op-alert" class="alert d-none" role="alert"></div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header">
                        <input type="search" id="op-search" class="form-control" placeholder="Search by name, SKU or scan barcode…"
                            autocomplete="off" aria-label="Search products">
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="op-products" style="max-height: 460px; overflow:auto"></ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header"><h6 class="mb-0">Cart</h6></div>
                    <div class="card-body">
                        <div id="op-cart-empty" class="text-600">No items yet. Tap a product to add it.</div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-2 d-none" id="op-cart-table">
                                <thead><tr><th>Item</th><th style="width:110px">Qty</th><th class="text-end">Total</th><th></th></tr></thead>
                                <tbody id="op-cart"></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-0 mb-3">
                            <span>Total</span><span id="op-total">0.00</span>
                        </div>

                        <div class="mb-2">
                            <label for="op-customer" class="form-label mb-1">Customer</label>
                            <select id="op-customer" class="form-select form-select-sm"><option value="">Walk-in customer</option></select>
                        </div>
                        <div class="mb-3">
                            <label for="op-payment" class="form-label mb-1">Payment method</label>
                            <select id="op-payment" class="form-select form-select-sm">
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="credit">Credit/Invoice (customer required)</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" id="op-clear" class="btn btn-falcon-default btn-sm">Clear</button>
                            <button type="button" id="op-save" class="btn btn-primary flex-grow-1">
                                <span class="fas fa-check me-1"></span> Save sale
                            </button>
                        </div>
                        <div class="fs--1 text-600 mt-2">Prices and stock are confirmed by the server when the sale syncs.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Sales on this device</h6></div>
            <div class="card-body p-0">
                <div id="op-queue-empty" class="p-3 text-600">Nothing waiting. Everything is synced.</div>
                <ul class="list-group list-group-flush" id="op-queue"></ul>
            </div>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('assets/js/offline-pos.js') }}"></script>
    @endpush
</x-app-layout>

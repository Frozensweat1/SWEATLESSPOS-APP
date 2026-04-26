<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

    <div class="container py-4">
        <!-- Page Title -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4">{{ $componentName }} | {{ $pageTitle }}</h2>
            <div class="btn-group">
                <a href="{{ route('make.sales') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">New Sale</span>
                </a>
                <!-- Export Button -->
                <button class="btn btn-falcon-primary btn-sm d-flex" type="button" wire:click="export('Csv')">
                    <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('Csv')">ing...</span> To CSV</span>
                    <span wire:loading wire:target="export('Csv')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="export('Xlsx')">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('Xlsx')">ing...</span> XLSX</span>
                    <span wire:loading wire:target="export('Xlsx')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-4 card">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by Customer"
                        wire:model.live="searchTerm">
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model.live="paymentMethodFilter">
                        <option value="">All Payment Methods</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="mobile_payment">Mobile Payment</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" wire:model="filter">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button wire:click="loadDateRange"
                        class="btn btn-sm btn-outline-primary rounded-pill me-1 d-flex align-items-center w-100"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                        <!-- Spinner -->
                        <span wire:loading wire:target="loadDateRange" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>

                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline"><i class="fas fa-recycle me-1"></i>
                            <span wire:loading.remove wire:target="loadDateRange">
                                Apply Filter
                            </span>
                        </span>
                        <span wire:loading wire:target="loadDateRange"> Applying Filter...</span>
                        <span class="d-md-none">
                            <i class="fas fa-recycle me-1"></i>
                        </span></button>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card">
            <div class="border-bottom card-header bg-light">
                <h5 class="mb-0 fs-0">Sales Records</h5>
            </div>
            <div class="p-0 card-body">
                <div class="table-responsive scrollbar">
                    <table class="table mb-0 overflow-hidden table-sm table-striped fs--1" style="min-width: 920px;">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Sale ID</th>
                                <th class="text-nowrap">Customer</th>
                                <th class="text-nowrap">Items</th>
                                <th class="text-nowrap">Total Amount</th>
                                <th class="text-nowrap">Payment Method</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Date</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="text-nowrap">#{{ $sale->id }}</td>
                                    <td class="text-nowrap">{{ $sale->customer ? $sale->customer->name : 'Guest' }}</td>
                                    <td class="text-nowrap">{{ $sale->saleDetails->count() }} item(s)</td>
                                    <td class="text-nowrap">Ghs {{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="text-nowrap">{{ ucfirst($sale->payment_method) }}</td>
                                    <td class="text-nowrap">
                                        @if ($sale->payment_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($sale->payment_status === 'partial')
                                            <span class="badge bg-info">Partial</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">{{ $sale->created_at->format('Y-m-d') }}</td>

                                    <td class="py-2 align-middle white-space-nowrap text-end">
                                        <div class="dropdown font-sans-serif position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                                <span class="fas fa-ellipsis-h fs--1"></span>
                                            </button>
                                            <div class="py-0 border dropdown-menu dropdown-menu-end"
                                                aria-labelledby="customer-dropdown-0">
                                                <div class="py-2 bg-white">
                                                    <a class="dropdown-item text-primary"
                                                        wire:click="viewReceipt({{ $sale->id }})"
                                                        href="javascript:void(0)">
                                                        <i class="fas fa-eye me-2"></i>View Receipt
                                                    </a>
                                                    <hr class="my-1">
                                                    <a class="dropdown-item text-success"
                                                        wire:click="Refund({{ $sale->saleDetails->first()?->id }})"
                                                        href="javascript:void(0)">
                                                        <i class="fas fa-undo me-2"></i>Refund
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
    @if ($isRefundMode)
        @include('livewire.refund-formmodal')
    @endif

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
                    <div class="modal-body" id="viewSaleReceipt"
                        style="font-family: 'Courier New', monospace; font-size: 14px; color: #333;">

                        <!-- Receipt Header -->
                        <div
                            style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 15px;">
                            <img src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}" alt="Company Logo"
                                style="height: 60px; margin-bottom: 10px;">
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
                            <p style="margin: 5px 0;"><strong>{{ $settings->site_name ?? 'Sweatless POS' }}</strong>
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
                                            <td style="text-align: center; padding: 8px 0;">{{ $detail->quantity }}
                                            </td>
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
                                    <span style="color: #ffc107; font-weight: bold; font-size: 16px;">PENDING</span>
                                @else
                                    <span style="color: #17a2b8; font-weight: bold; font-size: 16px;">PARTIAL</span>
                                @endif
                            </p>
                        </div>

                        <!-- Footer -->
                        <div
                            style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px dashed #666; font-size: 14px;">
                            <p style="margin: 8px 0; font-weight: bold;">
                                {{ $settings->receipt_footer ?? 'Thank you for your purchase!' }}</p>
                            <p style="margin: 8px 0; font-size: 12px; color: #666;">Please keep this receipt for your
                                records</p>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button class="btn btn-secondary" type="button" wire:click="closeViewReceipt">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button class="btn btn-info" type="button" onclick="printViewSaleReceipt()">
                            <i class="fas fa-print me-2"></i>Print
                        </button>
                        <button class="btn btn-success" type="button" onclick="downloadViewSaleReceipt()">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @once
        @push('js')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        @endpush
    @endonce

    @script
        <script>
            window.printViewSaleReceipt = function() {
                var receiptContent = document.getElementById('viewSaleReceipt');
                var printWindow = window.open('', '', 'width=800,height=600');
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

            window.downloadViewSaleReceipt = function() {
                const element = document.getElementById('viewSaleReceipt');
                const opt = {
                    margin: 5,
                    filename: 'receipt_sale.pdf',
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

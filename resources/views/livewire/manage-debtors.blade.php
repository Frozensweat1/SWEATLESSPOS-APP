<div>
    <div class="container-fluid">
        <!-- Title and Search Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-users-cog me-2"></i>{{ $componentName }}
                        </h4>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search debtors..."
                            wire:model.live="search">
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Table Card -->
        <div class="card">
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label mb-0 fw-bold">Filter by Status</label>
                        <select class="form-select form-select-sm" wire:model.live="filterStatus">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Debtors Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Total Amount</th>
                                <th>Amount Paid</th>
                                <th>Amount Due</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debtors as $debtor)
                                <tr>
                                    <td>{{ $debtor->customer->name ?? 'N/A' }}</td>
                                    <td>{{ $debtor->customer->email ?? 'N/A' }}</td>
                                    <td>{{ $debtor->customer->phone ?? 'N/A' }}</td>
                                    <td>GHS{{ number_format($debtor->total_amount, 2) }}</td>
                                    <td>GHS{{ number_format($debtor->amount_paid, 2) }}</td>
                                    <td><strong>GHS{{ number_format($debtor->amount_due, 2) }}</strong></td>
                                    <td>
                                        @if ($debtor->payment_status === 'pending')
                                            <span class="badge bg-danger">Pending</span>
                                        @elseif($debtor->payment_status === 'partial')
                                            <span class="badge bg-warning">Partial</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </td>
                                    <td>{{ $debtor->due_date ? \Carbon\Carbon::parse($debtor->due_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        <button wire:click="recordPayment({{ $debtor->id }})"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i>Record Payment
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-4 text-center text-muted">
                                        <i class="fas fa-inbox me-2"></i>No debtors found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $debtors->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    @if ($showPaymentModal && $selectedSale)
        <div class="modal fade show" id="paymentModal" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="text-white modal-header bg-primary">
                        <h5 class="modal-title">
                            <i class="fas fa-credit-card me-2"></i>Record Payment
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closePaymentModal()"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Customer Info -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer</label>
                            <p class="form-control-plaintext">{{ $selectedSale->customer->name ?? 'N/A' }}</p>
                        </div>

                        <!-- Amount Due -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Amount Due</label>
                            <p class="form-control-plaintext text-danger" style="font-size: 1.25rem;">
                                GHS{{ number_format($selectedSale->amount_due, 2) }}
                            </p>
                        </div>

                        <!-- Payment Amount Input -->
                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label fw-bold">Payment Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">GHS</span>
                                <input type="number" class="form-control" id="paymentAmount"
                                    wire:model.live="paymentAmount" placeholder="0.00" step="0.01" min="0"
                                    max="{{ $selectedSale->amount_due }}" />
                            </div>
                            @error('paymentAmount')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label fw-bold">Payment Method</label>
                            <select class="form-select" id="paymentMethod" wire:model="paymentMethod">
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <!-- Payment Summary -->
                        @if ($paymentAmount > 0)
                            <div class="alert alert-info">
                                <p class="mb-2"><strong>New Amount Due:</strong>
                                    GHS{{ number_format(max(0, $selectedSale->amount_due - $paymentAmount), 2) }}</p>
                                <p class="mb-0"><strong>Payment Status:</strong>
                                    @if ($selectedSale->amount_due - $paymentAmount <= 0)
                                        <span class="badge bg-success">Fully Paid</span>
                                    @else
                                        <span class="badge bg-warning">Partial Payment</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closePaymentModal()">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="confirmPayment()">
                            <i class="fas fa-check me-2"></i>Record Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div class="modal-backdrop fade show" style="display: block;"></div>
    @endif
</div>

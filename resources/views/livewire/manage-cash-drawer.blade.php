<div>
    <div class="container py-4">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">{{ $componentName }} | {{ $pageTitle }}</h2>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-cash-register me-2"></i>Current Session
                </h6>
            </div>
            <div class="card-body">
                @if ($openSession)
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="small text-muted">Opened At</div>
                            <div class="fw-semibold">{{ $openSession->opened_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Opening Balance</div>
                            <div class="fw-semibold">Ghs {{ number_format($openSession->opening_balance, 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Sales</div>
                            <div class="fw-semibold">
                                Ghs {{ number_format($liveSummary['cash_sales'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Refunds</div>
                            <div class="fw-semibold">
                                Ghs {{ number_format($liveSummary['cash_refunds'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Cash Expenses</div>
                            <div class="fw-semibold">
                                Ghs {{ number_format($liveSummary['cash_expenses'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Expected Cash</div>
                            <div class="fw-semibold">
                                Ghs {{ number_format($liveSummary['expected_cash'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Discrepancy (Preview)</div>
                            @php
                                $preview = $liveSummary['discrepancy'] ?? null;
                            @endphp
                            <div
                                class="fw-semibold {{ $preview > 0 ? 'text-success' : ($preview < 0 ? 'text-danger' : '') }}">
                                @if ($preview === null)
                                    —
                                @else
                                    Ghs {{ number_format($preview, 2) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Closing Balance</label>
                            <input type="number" min="0" step="0.01" class="form-control"
                                wire:model="closing_balance" placeholder="0.00">
                            @error('closing_balance')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Closing Note</label>
                            <input type="text" class="form-control" wire:model="closing_note"
                                placeholder="Optional note">
                            @error('closing_note')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-falcon-danger btn-sm d-flex align-items-center" type="button"
                            wire:click="closeDrawer" wire:loading.attr="disabled">
                            <span class="fas fa-lock" data-fa-transform="shrink-3 down-2"></span>
                            <span class="ms-1">Close Drawer</span>
                            <span wire:loading wire:target="closeDrawer"
                                class="spinner-border spinner-border-sm ms-2" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                @else
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Opening Balance</label>
                            <input type="number" min="0" step="0.01" class="form-control"
                                wire:model="opening_balance" placeholder="0.00">
                            @error('opening_balance')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Opening Note</label>
                            <input type="text" class="form-control" wire:model="opening_note"
                                placeholder="Optional note">
                            @error('opening_note')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-end">
                        <button class="btn btn-falcon-primary btn-sm d-flex align-items-center" type="button"
                            wire:click="openDrawer" wire:loading.attr="disabled">
                            <span class="fas fa-unlock" data-fa-transform="shrink-3 down-2"></span>
                            <span class="ms-1">Open Drawer</span>
                            <span wire:loading wire:target="openDrawer"
                                class="spinner-border spinner-border-sm ms-2" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search session ID"
                            wire:model.live="search">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="statusFilter">
                            <option value="">All Status</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="startDate">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" wire:model.live="endDate">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" wire:model.live="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="text-nowrap">Session</th>
                                <th class="text-nowrap">User</th>
                                <th class="text-nowrap">Opened</th>
                                <th class="text-nowrap">Closed</th>
                                <th class="text-nowrap">Opening</th>
                                <th class="text-nowrap">Expected</th>
                                <th class="text-nowrap">Expenses</th>
                                <th class="text-nowrap">Closing</th>
                                <th class="text-nowrap">Discrepancy</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sessions as $session)
                                <tr>
                                    <td class="text-nowrap">#{{ $session->id }}</td>
                                    <td class="text-nowrap">{{ $session->user?->name ?? 'N/A' }}</td>
                                    <td class="text-nowrap">
                                        {{ $session->opened_at?->format('Y-m-d H:i') ?? 'N/A' }}
                                    </td>
                                    <td class="text-nowrap">
                                        {{ $session->closed_at?->format('Y-m-d H:i') ?? '—' }}
                                    </td>
                                    <td class="text-nowrap">Ghs {{ number_format($session->opening_balance, 2) }}</td>
                                    <td class="text-nowrap">Ghs {{ number_format($session->expected_cash, 2) }}</td>
                                    <td class="text-nowrap">Ghs {{ number_format($session->cash_expenses, 2) }}</td>
                                    <td class="text-nowrap">
                                        {{ $session->closing_balance !== null ? 'Ghs ' . number_format($session->closing_balance, 2) : '—' }}
                                    </td>
                                    <td
                                        class="text-nowrap {{ $session->discrepancy > 0 ? 'text-success' : ($session->discrepancy < 0 ? 'text-danger' : '') }}">
                                        {{ $session->status === 'closed' ? 'Ghs ' . number_format($session->discrepancy, 2) : '—' }}
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="badge bg-{{ $session->status === 'open' ? 'warning' : 'success' }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-info text-center mb-0">No sessions found.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</div>

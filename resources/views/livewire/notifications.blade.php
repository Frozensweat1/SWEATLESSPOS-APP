<div>
    <!-- Filter Section Card -->
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h5>Filter Notifications</h5>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div class="flex-fill me-2">
                    <input wire:model.debounce.500ms="search" type="text" placeholder="Search notifications..."
                        class="form-control">
                </div>
                <div class="flex-fill me-2">
                    <select wire:model="filterType" class="form-select">
                        <option value="">All Types</option>
                        <option value="success">Success</option>
                        <option value="info">Info</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>
                </div>
                <div class="flex-fill">
                    <select wire:model="filterReadStatus" class="form-select">
                        <option value="">All</option>
                        <option value="read">Read</option>
                        <option value="unread">Unread</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Section Card -->
    <div class="card shadow-sm mb-3">
        <div class="card-header">
            <h5>Actions</h5>
        </div>
        <div class="card-body d-flex justify-content-between">
            <label for="selectAll">Select All Notifications</label>
            <input type="checkbox" id="selectAll" wire:model.live="selectAll" class="form-check-input me-2">
            <div class="btn-group">
                <!-- Export Button -->
                <button class="btn btn-falcon-primary btn-sm d-flex" type="button" wire:click="export('csv')">
                    <span class="fas fa-file-csv" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('csv')">ing...</span> CSV</span>
                    <span wire:loading wire:target="export('csv')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <button class="btn btn-falcon-success btn-sm d-flex" type="button" wire:click="export('xlsx')">
                    <span class="fas fa-file-excel" data-fa-transform="shrink-3 down-2"></span>
                    <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                            wire:target="export('xlsx')">ing...</span> Excel</span>
                    <span wire:loading wire:target="export('xlsx')" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>

                <!-- Bulk Delete Button -->
                <button class="btn btn-danger btn-sm d-flex" type="button" wire:click="confirmBulkDelete"
                    {{ empty($selectedNotifications) ? 'disabled' : '' }}>
                    <span class="fas fa-trash-alt"></span>
                    <span class="d-none d-sm-inline-block ms-1">Delete Selected</span>
                    <span wire:loading wire:target="confirmBulkDelete" class="spinner-border spinner-border-sm ms-1"
                        role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications Section Card -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5>Notifications</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($notifications as $notification)
                    <div class="col-md-6 mb-3">
                        <div
                            class="alert alert-{{ $notification->type }} d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="alert-heading">{{ $notification->title }}</h6>
                                <p class="mb-1">{{ $notification->message }}</p>
                                <small class="text-muted">{{ $notification->is_read ? 'Read' : 'Unread' }} -
                                    {{ ucfirst($notification->type) }}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="checkbox" wire:model="selectedNotifications"
                                    value="{{ $notification->id }}" class="form-check-input me-2">
                                <button wire:click="delete({{ $notification->id }})"
                                    class="btn btn-sm btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">No notifications found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>

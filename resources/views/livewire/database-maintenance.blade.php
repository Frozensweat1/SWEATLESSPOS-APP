<div class="container mb-4">
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><span class="fas fa-database me-2"></span>Database Backup</h5>
            <button type="button" class="btn btn-primary btn-sm" wire:click="createBackup" wire:loading.attr="disabled"
                wire:target="createBackup">
                <span wire:loading.remove wire:target="createBackup"><span class="fas fa-plus me-1"></span> Create backup</span>
                <span wire:loading wire:target="createBackup"><span class="spinner-border spinner-border-sm me-1"></span> Backing up…</span>
            </button>
        </div>
        <div class="card-body">
            @if (count($backups))
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr><th>File</th><th>Size</th><th>Created</th><th class="text-end">Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($backups as $b)
                                <tr wire:key="backup-{{ $b['name'] }}">
                                    <td>{{ $b['name'] }}</td>
                                    <td>{{ number_format($b['size'] / 1024, 1) }} KB</td>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($b['created_at'])->format('M d, Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('system.backup.download', $b['name']) }}" class="btn btn-falcon-default btn-sm">
                                            <span class="fas fa-download"></span>
                                        </a>
                                        <button type="button" class="btn btn-falcon-danger btn-sm"
                                            wire:click="deleteBackup('{{ $b['name'] }}')"
                                            wire:confirm="Delete this backup?">
                                            <span class="fas fa-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-600 mb-0">No backups yet. Create one to download a copy of the database.</p>
            @endif
        </div>
    </div>

    <div class="card border border-danger">
        <div class="card-header bg-soft-danger">
            <h5 class="mb-0 text-danger"><span class="fas fa-exclamation-triangle me-2"></span>Danger Zone: Truncate Data</h5>
        </div>
        <div class="card-body">
            <p class="mb-2">This permanently deletes all sales, products, customers, categories, suppliers, expenses,
                inventory, logs and other business data.</p>
            <p class="mb-3"><strong>Kept:</strong> super-admin users, the default store (ID 1), roles and permissions,
                system settings and website settings/pages. All other users and stores are removed.</p>

            <form wire:submit="truncateData" class="row g-3">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="backupFirst" wire:model="backupFirst">
                        <label class="form-check-label" for="backupFirst">Create a backup automatically before truncating (recommended)</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="confirmText" class="form-label">Type <code>DELETE ALL DATA</code> to confirm</label>
                    <input type="text" id="confirmText" class="form-control @error('confirmText') is-invalid @enderror"
                        wire:model="confirmText" autocomplete="off">
                    @error('confirmText') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="truncate_password" class="form-label">Your password</label>
                    <input type="password" id="truncate_password" class="form-control @error('password') is-invalid @enderror"
                        wire:model="password" autocomplete="current-password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-danger" wire:loading.attr="disabled" wire:target="truncateData"
                        wire:confirm="This cannot be undone. Continue?">
                        <span wire:loading.remove wire:target="truncateData"><span class="fas fa-trash-alt me-1"></span> Truncate data</span>
                        <span wire:loading wire:target="truncateData"><span class="spinner-border spinner-border-sm me-1"></span> Working…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

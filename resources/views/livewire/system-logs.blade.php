<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="container">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">System Logs</h5>
                <div>
                    <button wire:click="export" class="btn btn-secondary btn-sm">Export</button>
                    <button wire:loading.attr="disabled" wire:click="deleteSelected" class="btn btn-danger btn-sm ms-2">
                        Delete Selected
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap mb-3">
                    <input type="text" wire:model.live="search" placeholder="Search logs..."
                        class="form-control me-2 mb-2" style="max-width: 180px;">

                    <input type="date" wire:model.live="dateFrom" class="form-control me-2 mb-2"
                        style="max-width: 180px;">
                    <input type="date" wire:model.live="dateTo" class="form-control me-2 mb-2"
                        style="max-width: 180px;">

                    <select wire:model.live="selectedUser" class="form-control me-2 mb-2" style="max-width: 180px;">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" wire:model.live="selectAll"></th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td><input type="checkbox" wire:model="selectedLogs" value="{{ $log->id }}">
                                    </td>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>
                                        <button wire:loading.attr="disabled" wire:click="deleteLog({{ $log->id }})"
                                            class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div wire:loading wire:target="deleteLog, deleteSelected, export" class="spinner-border text-primary"
                    role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

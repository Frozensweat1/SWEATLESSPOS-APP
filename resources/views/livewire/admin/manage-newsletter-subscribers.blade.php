<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Newsletter Subscribers</h3>
            <button wire:click="exportCsv" class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Export CSV
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search by email or name...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filterStatus" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="col-md-5 text-right">
                    <span class="badge bg-primary">Total: {{ $totalSubscribers }}</span>
                    <span class="badge bg-success">Active: {{ $activeSubscribers }}</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber->name ?? '-' }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $subscriber->is_active ? 'success' : 'secondary' }}">
                                        {{ $subscriber->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $subscriber->subscribed_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($subscriber->is_active)
                                        <button wire:click="unsubscribe({{ $subscriber->id }})" class="btn btn-warning btn-sm" title="Unsubscribe">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    @else
                                        <button wire:click="resubscribe({{ $subscriber->id }})" class="btn btn-info btn-sm" title="Resubscribe">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $subscriber->id }})" wire:confirm="Are you sure you want to delete this subscriber?" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No subscribers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $subscribers->links() }}
            </div>
        </div>
    </div>
</div>
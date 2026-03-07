<div>
    <div class="px-2 py-3 container-fluid px-sm-3 px-md-4">
        <div class="mb-4 row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-0">{{ $pageTitle }} > {{ $componentName }}</h1>
                        <p class="mt-1 text-muted small">Manage all store locations and configurations</p>
                    </div>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add New Store
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-4 card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.live="searchengine" class="form-control"
                            placeholder="Search by store name, email, or phone...">
                    </div>
                    <div class="gap-2 col-md-6 d-flex">
                        <button class="btn btn-outline-danger" @disabled(count($selectedStores) === 0) wire:click="bulkDelete">
                            <i class="fas fa-trash me-2"></i>Delete Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stores Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table mb-0 table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="w-5">
                                <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                            </th>
                            <th>Store Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Initial Balance</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stores as $store)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="selectedStores" value="{{ $store->id }}"
                                        class="form-check-input" @if ($store->id === 1) disabled @endif>
                                </td>
                                <td>
                                    <div class="gap-2 d-flex align-items-center">
                                        @if ($store->logo_path)
                                            <img src="{{ asset('storage/' . $store->logo_path) }}"
                                                alt="{{ $store->name }}" class="rounded-circle" width="32"
                                                height="32">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-store fa-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="mb-0">{{ $store->name }}</p>
                                            <small class="text-muted">{{ $store->address }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $store->email }}</td>
                                <td>{{ $store->phone }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ config('app.currency_symbol', 'GHS') }}{{ number_format($store->initial_balance, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge @if ($store->is_active) bg-success @else bg-danger @endif">
                                        {{ $store->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if ($store->id === 1)
                                        <span class="badge bg-warning ms-1">Default</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" wire:click="Edit({{ $store->id }})"
                                        title="Edit Store">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if ($store->id !== 1)
                                        <button class="btn btn-sm btn-danger" wire:click="Delete({{ $store->id }})"
                                            title="Delete Store">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">
                                    <p class="mb-0 text-muted">
                                        <i class="mb-2 fas fa-inbox fa-2x d-block"></i>
                                        No stores found
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">Showing {{ $stores->count() }} of {{ $stores->total() }}
                            stores
                        </p>
                    </div>
                    <div>
                        {{ $stores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Modal -->
    <div class="modal @if ($isEditMode) show @endif"
        @if ($isEditMode) style="display: block; background-color: rgba(0,0,0,.5);" @endif
        tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ $selected_id ? 'Edit Store' : 'Create New Store' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="@if ($selected_id) Update @else Store @endif">
                        <div class="row g-3">
                            <!-- Store Name -->
                            <div class="col-md-6">
                                <label class="form-label">Store Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter store name">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" wire:model="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="store@example.com">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" wire:model="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="+1 (555) 000-0000">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Initial Balance -->
                            <div class="col-md-6">
                                <label class="form-label">Initial Balance <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">{{ config('app.currency_symbol', 'GHS') }}</span>
                                    <input type="number" wire:model="initial_balance"
                                        class="form-control @error('initial_balance') is-invalid @enderror"
                                        placeholder="0.00" step="0.01" min="0">
                                    @error('initial_balance')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea wire:model="address" class="form-control @error('address') is-invalid @enderror" rows="2"
                                    placeholder="Enter store address"></textarea>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Logo -->
                            <div class="col-12">
                                <label class="form-label">Store Logo</label>
                                @if ($currentLogo)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $currentLogo) }}" alt="Logo"
                                            class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                @endif
                                <input type="file" wire:model="logo_path"
                                    class="form-control @error('logo_path') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">Max 2MB. Recommended: 200x200px</small>
                                @error('logo_path')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input type="checkbox" wire:model="is_active" class="form-check-input"
                                        id="isActive">
                                    <label class="form-check-label" for="isActive">
                                        Store is Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal()">Cancel</button>
                    <button type="button" class="btn btn-primary"
                        wire:click="@if ($selected_id) Update @else Store @endif">
                        {{ $selected_id ? 'Update Store' : 'Create Store' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    @if ($isEditMode)
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

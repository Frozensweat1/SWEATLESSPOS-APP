<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <div class="card mb-3" id="customersTable">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ $componentName }} | {{ $pageTitle }}</h5>
                </div>

                <div class="col-8 col-sm-auto text-end ps-2">
                    <div id="table-customers-replace-element">
                        <button href="javascript:void(0)" class="btn btn-falcon-default btn-sm" type="button"
                            wire:click="create">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">New</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Search and Filters -->
            <div class="filters d-flex flex-column flex-md-row align-items-md-center gap-3 mt-2 mb-2">
                <!-- Search Input -->
                <div class="flex-grow-1">
                    <input type="text" class="form-control" wire:model.live="searchengine"
                        placeholder="Search Expenditures...">
                </div>

                <!-- Category Filter Dropdown -->
                <div>
                    <select class="form-select" wire:model.live="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter Input -->
                <div>
                    <input type="date" class="form-control" wire:model.live="dateFilter">
                </div>

                <!-- Reset Button -->
                <div>
                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="resetFilters">
                        <span class="fas fa-times" data-fa-transform="shrink-3 down-2"></span>
                        <span class="d-none d-sm-inline-block ms-1">Reset Filters</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">

                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>

                    </thead>
                    <tbody class="list" id="table-customers-body">
                        @forelse ($expenditures as $expenditure)
                            <tr class="btn-reveal-trigger">
                                <td>{{ $expenditure->description }}</td>
                                <td>{{ $expenditure->amount }}</td>
                                <td>{{ $expenditure->category }}</td>
                                <td>{{ $expenditure->expenditure_date->format('Y-m-d') }}</td>
                                <td>{{ $expenditure->supplier?->name ?? 'N/A' }}</td>
                                <td class="align-middle white-space-nowrap py-2 text-end">
                                    <div class="dropdown font-sans-serif position-static">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                            type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                            data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="customer-dropdown-0">
                                            <div class="bg-white py-2">
                                                <a class="dropdown-item" type="button"
                                                    wire:click="Edit({{ $expenditure->id }})">Edit</a>
                                                <a class="dropdown-item text-danger"
                                                    wire:click="Delete({{ $expenditure->id }})"
                                                    href="javascript:void(0)">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="alert alert-info text-center" role="alert">No record found!</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-center">
            <!-- Pagination -->
            {{ $expenditures->links() }}

        </div>
    </div>
    @if ($isEditMode)
        @include('livewire.expenditures-formmodal')
    @endif

</div>

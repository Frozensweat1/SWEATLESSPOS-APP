<div>
    <div class="card mb-3" id="customersTable">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ $componentName }} | {{ $pageTitle }}</h5>
                </div>

                <div class="col-8 col-sm-auto text-end ps-2">
                    <div id="table-suppliers-replace-element">
                        <!-- New Button -->
                        <button href="javascript:void(0)" class="btn btn-falcon-default btn-sm" type="button"
                            wire:click="create">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">New</span>
                            <span wire:loading wire:target="create" class="spinner-border spinner-border-sm ms-1"
                                role="status" aria-hidden="true"></span>
                        </button>

                        <!-- Export Button -->
                        <button class="btn btn-falcon-success btn-sm" type="button" wire:click="export">
                            <span class="fas fa-external-link-alt" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">Export<span wire:loading
                                    wire:target="export">ing...</span></span>
                            <span wire:loading wire:target="export" class="spinner-border spinner-border-sm ms-1"
                                role="status" aria-hidden="true"></span>
                        </button>

                        <!-- Bulk Delete Button -->
                        <button class="btn btn-danger btn-sm" type="button" wire:click="confirmBulkDelete"
                            {{ empty($selectedCustomers) ? 'disabled' : '' }}>
                            <span class="fas fa-trash-alt"></span>
                            <span class="d-none d-sm-inline-block ms-1">Delete Selected</span>
                            <span wire:loading wire:target="confirmBulkDelete"
                                class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>
                        </button>

                    </div>
                </div>
            </div>

            <!-- Date and Search Filters -->
            <div class="row mt-2 mb-2">
                <div class="col-md-4">
                    <input type="date" wire:model.live="startDate" class="form-control" placeholder="Start Date">
                </div>
                <div class="col-md-4">
                    <input type="date" wire:model.live="endDate" class="form-control" placeholder="End Date">
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live="searchengine" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th> <input class="form-check-input" type="checkbox" wire:model.live="selectAll"> </th>
                            <th class="text-nowrap" scope="col">Name</th>
                            <th class="text-nowrap" scope="col">Email</th>
                            <th class="text-nowrap" scope="col">Phone</th>
                            <th class="w-200">Address</th>
                            <th class="text-nowrap" scope="col">Points</th>
                            <th class="text-nowrap" scope="col">Joined</th>
                            <th class="text-nowrap" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="table-customers-body">
                        @forelse ($customers as $customer)
                            <tr class="btn-reveal-trigger">
                                <td class="text-nowrap">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="customer-{{ $customer->id }}" wire:model="selectedCustomers"
                                            value="{{ $customer->id }}" />
                                    </div>
                                </td>
                                <td class=" name text-nowrap">
                                    <a wire:click="Edit({{ $customer->id }})">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xl me-2">
                                                @if ($customer->image)
                                                    <img class="rounded-circle"
                                                        src="{{ asset('storage/' . $customer->image) }}"
                                                        alt="{{ $customer->name }}" />
                                                @else
                                                    <div class="avatar-name rounded-circle">
                                                        <span>
                                                            {{ collect(explode(' ', $customer->name))->take(2)->map(fn($word) => strtoupper($word[0]))->implode('') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">{{ $customer->name }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="email text-nowrap">
                                    <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                                </td>
                                <td class="text-nowrap">
                                    <a href="tel:{{ $customer->phone }}"> {{ $customer->phone }}</a>
                                </td>
                                <td class="w-200">{{ $customer->address }}</td>
                                <td class="text-nowrap"> {{ $customer->loyalty_points }}</td>
                                <td class="text-nowrap">{{ $customer->created_at->format('d/m/Y') }}</td>
                                <td class="align-middle">
                                    <div class="dropdown font-sans-serif position-static">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                            type="button" id="customer-dropdown-{{ $customer->id }}"
                                            data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="customer-dropdown-{{ $customer->id }}">
                                            <div class="bg-white py-2">
                                                <a class="dropdown-item" wire:click="Edit({{ $customer->id }})"
                                                    type="button">Edit</a>
                                                <a class="dropdown-item text-danger"
                                                    wire:click="Delete({{ $customer->id }})">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="alert alert-info text-center" role="alert">No record found!</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center">
            {{ $customers->links() }}
        </div>
    </div>

    @if ($isEditMode)
        @include('livewire.customer-formmodal')
    @endif
</div>

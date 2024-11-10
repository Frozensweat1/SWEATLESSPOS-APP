<div>
    <div class="card mb-3" id="suppliersTable">
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
                            <span class="d-none d-sm-inline-block ms-1">Export</span>
                            <span wire:loading wire:target="export" class="spinner-border spinner-border-sm ms-1"
                                role="status" aria-hidden="true"></span>
                        </button>

                        <!-- Bulk Delete Button -->
                        <button class="btn btn-danger btn-sm" type="button" wire:click="confirmBulkDelete"
                            {{ empty($selectedSuppliers) ? 'disabled' : '' }}>
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
                    <input type="text" wire:model.live="searchTerm" class="form-control" placeholder="Search...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">
                        <tr>
                            <th>
                                <input class="form-check-input" id="checkbox-bulk-suppliers-select" type="checkbox"
                                    wire:model.live="selectAll" />
                            </th>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Contact Person</th>
                            <th class="text-nowrap">Email</th>
                            <th class="text-nowrap">Phone</th>
                            <th class="text-nowrap ps-5" style="min-width: 200px;">Address</th>
                            <th class="text-nowrap ps-5" style="min-width: 200px;">Notes</th>
                            <th class="text-nowrap">Joined</th>
                            <th class="pe-1 align-middle ">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="table-suppliers-body">
                        @forelse ($suppliers as $supplier)
                            <tr class="btn-reveal-trigger">
                                <td style="width: 28px;">
                                    <input class="form-check-input" type="checkbox" wire:model="selectedSuppliers"
                                        value="{{ $supplier->id }}" />
                                </td>
                                <td class="text-nowrap">
                                    <div class="d-flex d-flex align-items-center">
                                        <h5 class="mb-0 fs--1">{{ $supplier->name }}</h5>
                                    </div>
                                </td>
                                <td class="text-nowrap">
                                    {{ $supplier->contact_person }}
                                </td>
                                <td class="text-nowrap">
                                    <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                </td>
                                <td class="text-nowrap">
                                    <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                </td>
                                <td class="text-wrap">{{ $supplier->address }}
                                </td>
                                <td class="text-wrap">
                                    {{ $supplier->notes }}
                                </td>
                                <td class="text-nowrap">{{ $supplier->created_at->format('d/m/Y') }}</td>
                                <td class="align-middle white-space-nowrap py-2 text-end">
                                    <div class="dropdown font-sans-serif position-static">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                            type="button" id="supplier-dropdown-{{ $supplier->id }}"
                                            data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="supplier-dropdown-{{ $supplier->id }}">
                                            <div class="bg-white py-2">
                                                <a class="dropdown-item" type="button"
                                                    wire:click="Edit({{ $supplier->id }})">Edit</a>
                                                <a class="dropdown-item text-danger"
                                                    wire:click="Delete({{ $supplier->id }})"
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
            {{ $suppliers->links() }}
        </div>
    </div>

    @if ($isEditMode)
        @include('livewire.supplier-formmodal')
    @endif
</div>

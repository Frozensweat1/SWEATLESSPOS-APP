{{-- Do your work, then step back. --}}

<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

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
            <div class="d-flex align-items-center mt-2 mb-2">
                <input type="text" wire:model.live="searchengine" class="form-control" placeholder="Search...">

            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                    <thead class="bg-200 text-900">

                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-wrap" style="min-width: 200px;">
                                Description</th>
                            <th class="text-nowrap">Created At</th>
                            <th class="align-middle ">Action</th>
                        </tr>

                    </thead>
                    <tbody class="list" id="table-customers-body">
                        @forelse ($categories as $category)
                            <tr class="btn-reveal-trigger">

                                <td class="name text-nowrap">
                                    <a wire:click="Edit({{ $category->id }})">
                                        <div class="d-flex d-flex align-items-center">

                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">{{ $category->name }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="address text-wrap">
                                    {{ \Illuminate\Support\Str::limit($category->description, 50) }}
                                </td>
                                <td class="text-nowrap">{{ $category->created_at->format('d/m/Y') }}</td>
                                <td class="align-middle white-space-nowrap py-2">
                                    <div class="dropdown font-sans-serif position-static">
                                        <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                            type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                            data-boundary="window" aria-haspopup="true" aria-expanded="false">
                                            <span class="fas fa-ellipsis-h fs--1"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end border py-0"
                                            aria-labelledby="customer-dropdown-0">
                                            <div class="bg-white py-2">
                                                <a class="dropdown-item" wire:click="Edit({{ $category->id }})"
                                                    type="button">Edit</a>
                                                <a class="dropdown-item text-danger" type="button"
                                                    wire:click="Delete({{ $category->id }})">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="4">
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
            {{ $categories->links() }}

        </div>
    </div>

    @if ($isEditMode)
        @include('livewire.category-formmodal')
    @endif

</div>

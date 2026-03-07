<div>
    {{-- Be like water. --}}
    <div class="card">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">Users</h5>
                </div>
                <div class="col-8 col-sm-auto text-end ps-2">
                    <div id="table-customers-replace-element">
                        <a class="btn btn-falcon-default btn-sm" type="button" href="{{ route('add.users') }}">
                            <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">New</span>
                        </a>
                        <button wire:click="exportUsers" class="btn btn-falcon-success btn-sm align-items-center"
                            type="button" wire:loading.attr="disabled" wire:loading.class="disabled"
                            wire:loading.class.remove="btn-falcon-success" wire:loading.class.add="btn-success">
                            <span class="fas fa-external-link-alt" data-fa-transform="shrink-3 down-2"></span>
                            <span class="d-none d-sm-inline-block ms-1">Export</span>

                            <!-- Spinner displayed during loading -->
                            <span wire:loading wire:target="exportUsers" class="spinner-border spinner-border-sm me-2"
                                role="status" aria-hidden="true"></span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0" id="followers">System Users</h5>
                </div>
                <div class="col">
                    <div class="row g-0">
                        <div class="col">
                            <input class="form-control form-control-sm" type="text" placeholder="Search..."
                                wire:model.live="searchTerm" />
                        </div>
                        <div class="col d-md-block d-none">
                            <select class="form-select form-select-sm ms-2" aria-label=".form-select-sm example"
                                wire:model.live="roleFilter">
                                <option value="">Filter by role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body bg-light px-1 py-0">

            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <div class="row g-0 text-center fs--1">
                @forelse ($users as $user)
                    <div class="col-6 col-md-4 col-lg-3 col-xxl-2 mb-1">
                        <div class="bg-white dark__bg-1100 p-3 h-100">
                            @if ($user->profile_photo_path)
                                <a href="{{ route('edit.user', $user->id) }}">
                                    <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                                        src="{{ asset('storage/' . $user->profile_photo_path) }}" alt=""
                                        width="100" />
                                </a>
                            @else
                                <a href="{{ route('edit.user', $user->id) }}">
                                    <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                                        src="{{ asset('assets/img/avatar.png') }}" alt="Profile Photo"
                                        width="100" />
                                </a>
                            @endif

                            <h6 class="mb-1">
                                <a href="{{ route('edit.user', $user->id) }}">{{ $user->name }}</a>
                            </h6>
                            <p class="fs--2 mb-1"><a class="text-700"
                                    href="#!">{{ $user->getRoleNames()->join(', ') }}</a></p>
                            <div class="dropdown font-sans-serif position-static">
                                <button class="btn btn-sm dropdown-toggle p-1 dropdown-caret-none" type="button"
                                    data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                    aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs--1"></span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border py-0">
                                    <div class="bg-white py-2">
                                        <a class="dropdown-item" href="{{ route('edit.user', $user->id) }}">Edit</a>
                                        <a wire:click.prevent="Delete({{ $user->id }})"
                                            class="dropdown-item text-danger" href="javascript:void(0)">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info" role="alert">No record found!</div>
                @endforelse

            </div>
        </div>
        <!-- Pagination links -->
        {{ $users->links() }}
    </div>

</div>

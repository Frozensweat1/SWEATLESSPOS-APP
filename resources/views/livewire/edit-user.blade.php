<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0" data-anchor="data-anchor" id="basic-form">Update User form</h5>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="tab-content">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <form wire:submit.prevent="updateUser">
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-name">Name</label>
                        <input class="form-control @error('name')is-invalid @enderror" id="basic-form-name"
                            type="text" placeholder="Name" wire:model="name" />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-email">Email address</label>
                        <input class="form-control @error('email')is-invalid @enderror" id="basic-form-email"
                            type="email" placeholder="name@example.com" wire:model="email" />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-form-gender">User Role</label>
                        <select class="form-select" id="basic-form-gender" aria-label="Default select example"
                            wire:model="role">
                            <option value="">-- Select Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <input type="file" id="image" class="form-control" wire:model="image">
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if ($existingImage && !$image)
                            <img src="{{ asset('storage/' . $existingImage) }}" class="img-thumbnail mt-2"
                                width="100">
                        @endif

                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail mt-2" width="100">
                        @endif
                    </div>
                    <button type="submit"
                        class="btn btn-outline-primary rounded-pill me-1  d-flex align-items-center float-end"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary"
                        wire:target="updateUser">
                        <!-- Spinner -->
                        <span wire:loading wire:target="updateUser" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>

                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline"><i class="fas fa-recycle"></i> <span wire:loading.remove
                                wire:target="updateUser">Update
                                User</span></span>
                        <span wire:loading wire:target="updateUser"> Updating User...</span>
                        <span class="d-md-none">
                            <i class="fas fa-recycle"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

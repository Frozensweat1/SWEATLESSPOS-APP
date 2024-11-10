<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">User Profile</h5>
            </div>
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateProfile">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" wire:model.defer="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" wire:model.defer="email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" class="form-control" wire:model.defer="password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="profile_photo" class="form-label">Profile Photo</label>
                        <input type="file" id="profile_photo" class="form-control" wire:model="profile_photo">
                        @error('profile_photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if (Auth::user()->profile_photo_path && !$profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}"
                                class="img-thumbnail mt-2" width="100">
                        @endif
                        @if ($profile_photo)
                            <img src="{{ $profile_photo->temporaryUrl() }}" alt="Preview" class="img-thumbnail mt-2"
                                style="width: 100px;">
                        @endif
                    </div>
                    <button type="submit"
                        class="btn btn-outline-primary rounded-pill me-1  d-flex align-items-center float-end"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                        <!-- Spinner -->
                        <span wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>

                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline"><i class="fas fa-recycle"></i> <span wire:loading.remove>Update
                                Profile</span></span>
                        <span wire:loading wire:target="updateProfile"> Updating Profile...</span>
                        <span class="d-md-none">
                            <i class="fas fa-recycle"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

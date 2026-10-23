<div class="container-fluid mt-3" style="max-width: 960px">
    @if (session()->has('message'))
        <div class="alert alert-success d-flex align-items-center" role="status">
            <span class="fas fa-check-circle me-2"></span>{{ session('message') }}
        </div>
    @endif

    <form wire:submit="updateProfile">
        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        @php $photoPath = Auth::user()->profile_photo_path; @endphp
                        @if ($profile_photo)
                            <img src="{{ $profile_photo->temporaryUrl() }}" alt="New profile photo preview"
                                class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover">
                        @elseif ($photoPath)
                            <img src="{{ asset('storage/' . $photoPath) }}" alt="Your profile photo"
                                class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover">
                        @else
                            <div class="rounded-circle bg-soft-primary text-primary d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 120px; height: 120px; font-size: 2.75rem; font-weight: 700">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-600 fs--1 mb-3">{{ Auth::user()->email }}</p>

                        <label for="profile_photo" class="btn btn-falcon-default btn-sm w-100 mb-0">
                            <span class="fas fa-camera me-1"></span> Change photo
                        </label>
                        <input type="file" id="profile_photo" class="d-none" accept="image/*"
                            wire:model="profile_photo">
                        <div wire:loading wire:target="profile_photo" class="fs--1 text-600 mt-2">Uploading…</div>
                        <div class="fs--1 text-600 mt-2">JPG or PNG, up to 1 MB.</div>
                        @error('profile_photo')
                            <div class="text-danger fs--1 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Account details</h5>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full name</label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                    wire:model.blur="name" autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model.blur="email" autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Change password</h5>
                        <p class="fs--1 text-600 mb-0">Leave blank to keep your current password. You'll be logged out after changing it.</p>
                    </div>
                    <div class="card-body bg-light">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">New password</label>
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password" autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm new password</label>
                                <input type="password" id="password_confirmation" class="form-control"
                                    wire:model="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled"
                        wire:target="updateProfile,profile_photo">
                        <span wire:loading wire:target="updateProfile" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>
                        <span wire:loading.remove wire:target="updateProfile"><span class="fas fa-save me-1"></span> Save changes</span>
                        <span wire:loading wire:target="updateProfile">Saving…</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

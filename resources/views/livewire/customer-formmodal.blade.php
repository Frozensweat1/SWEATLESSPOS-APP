<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    @include('livewire.modals.large-modal-header')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <form>
        <div class="mb-3">
            <label class="form-label" for="basic-form-name">Name</label>
            <input class="form-control @error('name')is-invalid @enderror" id="basic-form-name" type="text"
                placeholder="Name" wire:model.defer="name" />
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-email">Email address</label>
            <input class="form-control @error('email')is-invalid @enderror" id="basic-form-email" type="email"
                placeholder="name@example.com" wire:model.defer="email" />
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-phone">Phone</label>
            <input class="form-control @error('phone')is-invalid @enderror" id="basic-form-phone" type="tel"
                placeholder="233 200000000" wire:model.defer="phone" />
            @error('phone')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-password">Address</label>
            <input class="form-control @error('address')is-invalid @enderror" id="basic-form-address" type="text"
                placeholder="address" wire:model.defer="address" />
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input class="form-control" type="file" wire:model.defer="image" />
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            @if ($currentImage && !$image)
                <img src="{{ asset('storage/' . $currentImage) }}" class="img-thumbnail mt-2" width="100">
            @endif

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail mt-2" width="100">
            @endif
        </div>
    </form>

    @include('livewire.modals.large-modal-footer')

</div>

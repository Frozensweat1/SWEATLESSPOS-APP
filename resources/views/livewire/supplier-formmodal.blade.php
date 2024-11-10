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
            <label class="form-label" for="basic-form-text">Contact Person</label>
            <input class="form-control @error('contact_person')is-invalid @enderror" id="basic-form-text" type="text"
                placeholder="Contact Person" wire:model.defer="contact_person" />
            @error('contact_person')
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
            <label class="form-label">Notes</label>
            <textarea class="form-control @error('notes')is-invalid @enderror" name="notes" id="" cols="30"
                rows="5" wire:model.defer="notes"></textarea>
            @error('notes')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
    </form>

    @include('livewire.modals.large-modal-footer')

</div>

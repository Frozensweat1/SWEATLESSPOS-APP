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
            <label class="form-label" for="basic-form-description">Description</label>
            <textarea class="form-control @error('description')is-invalid @enderror" name="description" id=""
                cols="30" rows="5" wire:model.defer="description"></textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input class="form-control" type="file" wire:model.defer="image" />
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
    </form>

    @include('livewire.modals.large-modal-footer')

</div>

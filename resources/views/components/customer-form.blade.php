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

      </div>
  </form>

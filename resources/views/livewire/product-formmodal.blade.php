<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    @include('livewire.modals.large-modal-header')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <form>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label" for="basic-form-name">Name</label>
                <input class="form-control @error('name')is-invalid @enderror" id="basic-form-name" type="text"
                    placeholder="Name" wire:model.defer="name" />
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="basic-form-text">Barcode</label>
                <input class="form-control @error('barcode')is-invalid @enderror" id="basic-form-text" type="text"
                    placeholder="Barcode" wire:model.lazy="barcode" autofocus />
                @error('barcode')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label" for="basic-form-price">Price</label>
                <input class="form-control @error('price')is-invalid @enderror" data-type="currency"
                    id="basic-form-price" type="number" placeholder="" wire:model.defer="price" />
                @error('price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="basic-form-text">Cost Price</label>
                <input class="form-control @error('cost_price')is-invalid @enderror" data-type="currency"
                    id="basic-form-text" type="number" wire:model.defer="cost_price" />
                @error('cost_price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="basic-form-text">Quantity</label>
                <input class="form-control @error('stock_quantity')is-invalid @enderror" id="basic-form-text"
                    type="number" wire:model.defer="stock_quantity" />
                @error('stock_quantity')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="basic-form-phone">Category</label>
                <select class="form-select @error('category_id')is-invalid @enderror" id="inputState"
                    wire:model.defer="category_id">
                    <option value="" disabled>Choose category...</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="basic-form-select">Supplier</label>
                <select class="form-select @error('supplier_id')is-invalid @enderror" id="inputState"
                    wire:model.defer="supplier_id">
                    <option value="" disabled>Choose Supplier...</option>

                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="basic-form-dob">Manufacture Date</label>
                <input class="form-control @error('manufacture_date')is-invalid @enderror" id="basic-form-dob"
                    type="date" wire:model.defer="manufacture_date" />
                @error('manufacture_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="basic-form-dob">Expiry Date</label>
                <input class="form-control @error('expiry_date')is-invalid @enderror" id="basic-form-dob" type="date"
                    wire:model.defer="expiry_date" />
                @error('expiry_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12">
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
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description')is-invalid @enderror" name="description" id=""
                        cols="30" rows="5" wire:model.defer="description"></textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

            </div>
        </div>
    </form>

    @include('livewire.modals.large-modal-footer')

</div>

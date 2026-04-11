<div>
    {{-- Success is as dangerous as failure. --}}
    @include('livewire.modals.large-modal-header')
    <form>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label" for="basic-form-description">Title</label>
            <input class="form-control @error('description')is-invalid @enderror" id="basic-form-description"
                type="text" placeholder="Expense Title" wire:model.defer="description" />
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-text">Amount</label>
            <input class="form-control @error('amount')is-invalid @enderror" id="basic-form-text" type="number"
                data-type="currency" placeholder="0.00" wire:model.defer="amount" />
            @error('amount')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-payment">Payment Method</label>
            <select class="form-select @error('payment_method')is-invalid @enderror" id="basic-form-payment"
                wire:model.defer="payment_method">
                <option value="" disabled>Select Payment Method...</option>
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
                <option value="mobile_money">Mobile Money</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
            @error('payment_method')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-email">Expense Category</label>
            <select class="form-select @error('category')is-invalid @enderror" id="inputState"
                wire:model.defer="category">
                <option value="" disabled>Choose Category...</option>
                <option value="utilities">utilities</option>
                <option value="supplies">supplies</option>
                <option value="maintenance">maintenance</option>
                <option value="salary">salary</option>
            </select>
            @error('category')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-date">Expenditure Date</label>
            <input class="form-control @error('expenditure_date')is-invalid @enderror" id="basic-form-date"
                type="date" wire:model.defer="expenditure_date" />
            @error('expenditure_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="basic-form-select">Supplier</label>
            <select class="form-select @error('supplier_id')is-invalid @enderror" id="inputState"
                wire:model.defer="supplier_id">
                <option value="" disabled>Choose Supplier...</option>

                <!-- Assuming you have a list of suppliers passed to the view -->
                @foreach (App\Models\Supplier::all() as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            @error('supplier_id')
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

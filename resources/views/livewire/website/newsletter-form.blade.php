<div class="py-2 px-2">
    <p class="small mb-3">Get the latest news and updates delivered to your inbox.</p>

    @if($successMessage)
        <div class="alert alert-success py-2 small">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit="subscribe" class="d-flex flex-column gap-2">
        <input type="text" wire:model="name" placeholder="Your name (optional)" 
            class="form-control form-control-sm bg-dark text-light border-secondary">

        <input type="email" wire:model="email" placeholder="your@email.com"
            class="form-control form-control-sm bg-dark text-light border-secondary @error('email') is-invalid @enderror">
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary btn-sm w-100" wire:loading.attr="disabled">
            <span wire:loading.remove>Subscribe</span>
            <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
        </button>
    </form>
</div>
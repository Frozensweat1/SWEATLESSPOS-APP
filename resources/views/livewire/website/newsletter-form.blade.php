<div>
    <p style="font-size:.9rem">Get the latest news and updates in your inbox.</p>

    @if ($successMessage)
        <div class="alert alert--ok" style="margin-bottom:.75rem">{{ $successMessage }}</div>
    @endif

    <form wire:submit="subscribe" class="form-grid" data-requires-online novalidate style="gap:.5rem">
        <p class="offline-note">You're offline.</p>
        <input type="text" class="input" wire:model="name" placeholder="Your name (optional)" aria-label="Your name" style="background:rgb(255 255 255 / .08);border-color:rgb(255 255 255 / .15);color:#fff">
        <input type="email" class="input" wire:model="email" placeholder="you@example.com" aria-label="Email address" style="background:rgb(255 255 255 / .08);border-color:rgb(255 255 255 / .15);color:#fff">
        @error('email')<div class="error" style="color:#fca5a5">{{ $message }}</div>@enderror
        <button type="submit" class="btn btn--block" wire:loading.attr="disabled">
            <span wire:loading.remove>Subscribe</span>
            <span wire:loading>Subscribing…</span>
        </button>
    </form>
</div>

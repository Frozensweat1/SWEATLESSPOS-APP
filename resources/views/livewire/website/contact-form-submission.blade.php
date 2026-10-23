<form wire:submit="submitForm" class="form-grid" data-requires-online novalidate>
    @if ($sent)<div class="alert alert--ok" role="status">Thank you! Your message has been sent. We'll get back to you soon.</div>@endif
    <p class="offline-note">You're offline — you can send this message once you're back online.</p>
    <div class="form-grid form-grid--2">
        <div>
            <label class="label" for="cf-name">Name</label>
            <input id="cf-name" type="text" class="input" wire:model="name" autocomplete="name">
            @error('name')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="label" for="cf-email">Email</label>
            <input id="cf-email" type="email" class="input" wire:model="email" autocomplete="email">
            @error('email')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="form-grid form-grid--2">
        <div>
            <label class="label" for="cf-phone">Phone (optional)</label>
            <input id="cf-phone" type="tel" class="input" wire:model="phone" autocomplete="tel">
            @error('phone')<div class="error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="label" for="cf-subject">Subject</label>
            <input id="cf-subject" type="text" class="input" wire:model="subject">
            @error('subject')<div class="error">{{ $message }}</div>@enderror
        </div>
    </div>
    <div>
        <label class="label" for="cf-message">Message</label>
        <textarea id="cf-message" class="textarea" rows="5" wire:model="message"></textarea>
        @error('message')<div class="error">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn--block btn--lg" wire:loading.attr="disabled">
        <span wire:loading.remove><x-site.icon name="send" :size="18" /> Send message</span>
        <span wire:loading>Sending…</span>
    </button>
</form>

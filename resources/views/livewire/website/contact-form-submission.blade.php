<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="card-title mb-4">Get in Touch</h2>
                    <p class="text-muted mb-4">Fill out the form below and we'll get back to you as soon as possible.</p>

                    <form wire:submit="submitForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name *</label>
                            <input type="text" wire:model="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Your name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email *</label>
                            <input type="email" wire:model="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="tel" wire:model="phone" class="form-control"
                                placeholder="+1 (555) 000-0000">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject *</label>
                            <input type="text" wire:model="subject"
                                class="form-control @error('subject') is-invalid @enderror"
                                placeholder="What is this about?">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message *</label>
                            <textarea wire:model="message" class="form-control @error('message') is-invalid @enderror" rows="5"
                                placeholder="Your message here..."></textarea>
                            @error('message')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove><i class="fas fa-paper-plane me-2"></i> Send Message</span>
                            <span wire:loading><i class="fas fa-spinner fa-spin me-2"></i> Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

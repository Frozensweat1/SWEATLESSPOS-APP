<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">Website Settings</h1>

            <div class="card">
                <div class="card-body">
                    <form wire:submit="saveSettings">
                        <h5 class="mb-3">General</h5>

                        <div class="mb-3">
                            <label class="form-label">Site Name *</label>
                            <input type="text" wire:model="settings.site_name"
                                class="form-control @error('settings.site_name') is-invalid @enderror">
                            @error('settings.site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Site Tagline</label>
                            <input type="text" wire:model="settings.site_tagline" class="form-control"
                                placeholder="Your business tagline">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Site Logo</label>
                            <input type="file" wire:model="logoImage" accept="image/*"
                                class="form-control @error('logoImage') is-invalid @enderror">
                            @error('logoImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($logoPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ basename($logoPreview) }}</small>
                                    <img src="{{ asset('storage/' . $logoPreview) }}" class="img-fluid mt-2 rounded"
                                        style="max-width: 150px; max-height: 100px; object-fit: contain;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Contact Information</h5>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="settings.email"
                                class="form-control @error('settings.email') is-invalid @enderror"
                                placeholder="contact@example.com">
                            @error('settings.email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" wire:model="settings.phone"
                                class="form-control @error('settings.phone') is-invalid @enderror"
                                placeholder="+1 (555) 000-0000">
                            @error('settings.phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea wire:model="settings.address" class="form-control" rows="3" placeholder="Your business address"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Map Embed URL</label>
                            <input type="text" wire:model="settings.map_url"
                                class="form-control @error('settings.map_url') is-invalid @enderror"
                                placeholder="https://www.google.com/maps/embed?pb=..">
                            <small class="text-muted d-block mt-2">Paste your Google Maps embed iframe URL. Leave empty
                                to auto-generate from address/coordinates.</small>
                            @error('settings.map_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Social Media Links</h5>

                        <div class="mb-3">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" wire:model="settings.facebook_url"
                                class="form-control @error('settings.facebook_url') is-invalid @enderror"
                                placeholder="https://facebook.com/yourpage">
                            @error('settings.facebook_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" wire:model="settings.instagram_url"
                                class="form-control @error('settings.instagram_url') is-invalid @enderror"
                                placeholder="https://instagram.com/yourpage">
                            @error('settings.instagram_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Twitter URL</label>
                            <input type="url" wire:model="settings.twitter_url"
                                class="form-control @error('settings.twitter_url') is-invalid @enderror"
                                placeholder="https://twitter.com/yourpage">
                            @error('settings.twitter_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="url" wire:model="settings.linkedin_url"
                                class="form-control @error('settings.linkedin_url') is-invalid @enderror"
                                placeholder="https://linkedin.com/company/yourcompany">
                            @error('settings.linkedin_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">TikTok URL</label>
                            <input type="url" wire:model="settings.tiktok_url"
                                class="form-control @error('settings.tiktok_url') is-invalid @enderror"
                                placeholder="https://tiktok.com/@yourpage">
                            @error('settings.tiktok_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" wire:model="settings.whatsapp_number"
                                class="form-control @error('settings.whatsapp_number') is-invalid @enderror"
                                placeholder="+233501234567">
                            @error('settings.whatsapp_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Hero Section Background</h5>

                        <div class="mb-3">
                            <label class="form-label">Background Image</label>
                            <input type="file" wire:model="heroBackgroundImage" accept="image/*"
                                class="form-control @error('heroBackgroundImage') is-invalid @enderror">
                            @error('heroBackgroundImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($heroBackgroundPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ basename($heroBackgroundPreview) }}</small>
                                    <img src="{{ asset('storage/' . $heroBackgroundPreview) }}"
                                        class="img-fluid mt-2 rounded"
                                        style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">About Section Background</h5>

                        <div class="mb-3">
                            <label class="form-label">Background Image</label>
                            <input type="file" wire:model="aboutBackgroundImage" accept="image/*"
                                class="form-control @error('aboutBackgroundImage') is-invalid @enderror">
                            @error('aboutBackgroundImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($aboutBackgroundPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current: {{ basename($aboutBackgroundPreview) }}</small>
                                    <img src="{{ asset('storage/' . $aboutBackgroundPreview) }}"
                                        class="img-fluid mt-2 rounded"
                                        style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Products Section Background</h5>

                        <div class="mb-3">
                            <label class="form-label">Background Image</label>
                            <input type="file" wire:model="productsBackgroundImage" accept="image/*"
                                class="form-control @error('productsBackgroundImage') is-invalid @enderror">
                            @error('productsBackgroundImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($productsBackgroundPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current:
                                        {{ basename($productsBackgroundPreview) }}</small>
                                    <img src="{{ asset('storage/' . $productsBackgroundPreview) }}"
                                        class="img-fluid mt-2 rounded"
                                        style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Gallery Section Background</h5>

                        <div class="mb-3">
                            <label class="form-label">Background Image</label>
                            <input type="file" wire:model="galleryBackgroundImage" accept="image/*"
                                class="form-control @error('galleryBackgroundImage') is-invalid @enderror">
                            @error('galleryBackgroundImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($galleryBackgroundPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current:
                                        {{ basename($galleryBackgroundPreview) }}</small>
                                    <img src="{{ asset('storage/' . $galleryBackgroundPreview) }}"
                                        class="img-fluid mt-2 rounded"
                                        style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Contact Section Background</h5>

                        <div class="mb-3">
                            <label class="form-label">Background Image</label>
                            <input type="file" wire:model="contactBackgroundImage" accept="image/*"
                                class="form-control @error('contactBackgroundImage') is-invalid @enderror">
                            @error('contactBackgroundImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($contactBackgroundPreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current:
                                        {{ basename($contactBackgroundPreview) }}</small>
                                    <img src="{{ asset('storage/' . $contactBackgroundPreview) }}"
                                        class="img-fluid mt-2 rounded"
                                        style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3">Founder / CEO Information</h5>

                        <div class="mb-3">
                            <label class="form-label">Founder/CEO Name</label>
                            <input type="text" wire:model="settings.founder_name" class="form-control"
                                placeholder="Enter founder name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title/Position</label>
                            <input type="text" wire:model="settings.founder_title" class="form-control"
                                placeholder="e.g., Founder & CEO">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Biography</label>
                            <textarea wire:model="settings.founder_bio" class="form-control" rows="4"
                                placeholder="Write a brief biography of the founder..."></textarea>
                            <small class="text-muted">Line breaks will be preserved</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Founder/CEO Image</label>
                            <input type="file" wire:model="founderImage" accept="image/*"
                                class="form-control @error('founderImage') is-invalid @enderror">
                            @error('founderImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($founderImagePreview)
                                <div class="mt-2">
                                    <small class="text-muted">Current:
                                        {{ basename($founderImagePreview) }}</small>
                                    <img src="{{ asset('storage/' . $founderImagePreview) }}" class="rounded mt-2"
                                        style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                wire:loading.class="opacity-50" wire:target="saveSettings">
                                <span wire:loading.remove wire:target="saveSettings">
                                    <i class="bi bi-check-circle"></i> Save Settings
                                </span>
                                <span wire:loading wire:target="saveSettings">
                                    <i class="fas fa-spinner fa-spin"></i> Saving...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Website Preview</h5>
                    <div class="small">
                        <p>
                            <strong>Site Name:</strong><br>
                            {{ $settings['site_name'] ?? 'Site Name' }}
                        </p>
                        <p>
                            <strong>Tagline:</strong><br>
                            {{ $settings['site_tagline'] ?? 'Your business tagline' }}
                        </p>
                        <p>
                            <strong>Contact:</strong><br>
                            📧 {{ $settings['email'] ?? 'email@example.com' }}<br>
                            📱 {{ $settings['phone'] ?? '+1 (555) 000-0000' }}<br>
                            📍 {{ Str::limit($settings['address'] ?? 'Your Address', 30) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card mt-3 bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Quick Links</h5>
                    <ul class="small list-unstyled">
                        <li><a href="/" class="text-white">View Website</a></li>
                        <li><a href="/admin/website-pages" class="text-white">Manage Pages</a></li>
                        <li><a href="/admin/gallery" class="text-white">Manage Gallery</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

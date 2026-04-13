<div class="container my-5">
    <h1 class="mb-4">Manage Gallery</h1>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'albums' ? 'active' : '' }}" wire:click="$set('tab', 'albums')">
                Albums
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $tab === 'images' ? 'active' : '' }}" wire:click="$set('tab', 'images')"
                @disabled(!$selectedAlbumId)>
                Images
            </button>
        </li>
    </ul>

    <!-- Albums Tab -->
    @if ($tab === 'albums')
        <div class="row mb-4">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $editingAlbumId ? 'Edit Album' : 'Create New Album' }}</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit="saveAlbum">
                            <div class="mb-3">
                                <label class="form-label">Album Name *</label>
                                <input type="text" wire:model="albumName"
                                    class="form-control @error('albumName') is-invalid @enderror"
                                    placeholder="e.g., Summer 2024">
                                @error('albumName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug *</label>
                                <input type="text" wire:model="albumSlug"
                                    class="form-control @error('albumSlug') is-invalid @enderror"
                                    placeholder="e.g., summer-2024">
                                @error('albumSlug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea wire:model="albumDescription" class="form-control" rows="3" placeholder="Album description"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" wire:model="albumDisplayOrder" class="form-control"
                                        min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" wire:model="albumIsActive" id="albumActive"
                                            class="form-check-input">
                                        <label class="form-check-label" for="albumActive">Active</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled"
                                wire:loading.class="opacity-50" wire:target="saveAlbum">
                                <span wire:loading.remove
                                    wire:target="saveAlbum">{{ $editingAlbumId ? 'Update' : 'Create' }} Album</span>
                                <span wire:loading wire:target="saveAlbum"><i class="fas fa-spinner fa-spin"></i>
                                    Saving...</span>
                            </button>
                            @if ($editingAlbumId)
                                <button type="button" wire:click="resetAlbumForm" class="btn btn-secondary w-100 mt-2"
                                    wire:loading.attr="disabled" wire:target="resetAlbumForm">
                                    <span wire:loading.remove wire:target="resetAlbumForm">Cancel</span>
                                    <span wire:loading wire:target="resetAlbumForm"><i
                                            class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Albums</h5>
                            </div>
                            <div class="col-auto">
                                <input type="text" wire:model.live="search" class="form-control"
                                    placeholder="Search..." style="width: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Images</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($albums as $album)
                                    <tr>
                                        <td><strong>{{ $album->name }}</strong></td>
                                        <td>{{ $album->images()->count() }} images</td>
                                        <td>
                                            @if ($album->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="editAlbum({{ $album->id }})"
                                                class="btn btn-sm btn-warning" wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50"
                                                wire:target="editAlbum({{ $album->id }})">
                                                <span wire:loading.remove
                                                    wire:target="editAlbum({{ $album->id }})">Edit</span>
                                                <span wire:loading wire:target="editAlbum({{ $album->id }})"><i
                                                        class="fas fa-spinner fa-spin"></i></span>
                                            </button>
                                            <button wire:click="selectAlbum({{ $album->id }})"
                                                class="btn btn-sm btn-info" wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50"
                                                wire:target="selectAlbum({{ $album->id }})">
                                                <span wire:loading.remove
                                                    wire:target="selectAlbum({{ $album->id }})">Images</span>
                                                <span wire:loading wire:target="selectAlbum({{ $album->id }})"><i
                                                        class="fas fa-spinner fa-spin"></i></span>
                                            </button>
                                            <button wire:click="deleteAlbum({{ $album->id }})"
                                                wire:confirm="Delete this album?" class="btn btn-sm btn-danger"
                                                wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                                wire:target="deleteAlbum({{ $album->id }})">
                                                <span wire:loading.remove
                                                    wire:target="deleteAlbum({{ $album->id }})">Delete</span>
                                                <span wire:loading wire:target="deleteAlbum({{ $album->id }})"><i
                                                        class="fas fa-spinner fa-spin"></i></span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No albums found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Images Tab -->
    @if ($tab === 'images' && $selectedAlbumId)
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Upload Image to {{ $currentAlbum->name ?? 'Album' }}</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit="uploadImage">
                            <div class="mb-3">
                                <label class="form-label">Image *</label>
                                <input type="file" wire:model="imageFile"
                                    class="form-control @error('imageFile') is-invalid @enderror" accept="image/*">
                                @error('imageFile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if ($imageFile)
                                    <small class="text-success">Image selected:
                                        {{ $imageFile->getClientOriginalName() }}</small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" wire:model="imageTitle" class="form-control"
                                    placeholder="Image title (optional)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea wire:model="imageDescription" class="form-control" rows="3" placeholder="Image description"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" wire:model="imageDisplayOrder" class="form-control"
                                        min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" wire:model="imageFeatured" id="imageFeatured"
                                            class="form-check-input">
                                        <label class="form-check-label" for="imageFeatured">Featured</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled"
                                wire:loading.class="opacity-50" wire:target="uploadImage">
                                <span wire:loading.remove wire:target="uploadImage">Upload Image</span>
                                <span wire:loading wire:target="uploadImage"><i class="fas fa-spinner fa-spin"></i>
                                    Uploading...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-0">Images in Album</h5>
                            </div>
                            <div class="col-auto">
                                <button wire:click="backToAlbums" class="btn btn-sm btn-secondary"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-50"
                                    wire:target="backToAlbums">
                                    <span wire:loading.remove wire:target="backToAlbums">Back to Albums</span>
                                    <span wire:loading wire:target="backToAlbums"><i
                                            class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row p-3">
                        @forelse($currentAlbum->images()->paginate(12) as $image)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                        alt="{{ $image->title }}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        @if ($image->title)
                                            <h6 class="card-title small mb-2">{{ $image->title }}</h6>
                                        @endif
                                        <div class="d-flex gap-1">
                                            <button wire:click="deleteImage({{ $image->id }})"
                                                wire:confirm="Delete this image?" wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50"
                                                wire:target="deleteImage({{ $image->id }})"
                                                class="btn btn-sm btn-danger flex-grow-1">
                                                <span wire:loading.remove
                                                    wire:target="deleteImage({{ $image->id }})">Delete</span>
                                                <span wire:loading wire:target="deleteImage({{ $image->id }})"><i
                                                        class="fas fa-spinner fa-spin"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                No images in this album yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
</div>
@endif
</div>

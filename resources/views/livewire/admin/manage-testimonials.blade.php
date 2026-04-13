<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Manage Testimonials</h3>
            <button wire:click="create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Testimonial
            </button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Search testimonials...">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Testimonial</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                            <tr>
                                <td>
                                    <strong>{{ $testimonial->author_name }}</strong>
                                    @if($testimonial->author_title)
                                        <br><small class="text-muted">{{ $testimonial->author_title }}</small>
                                    @endif
                                </td>
                                <td>{{ Str::limit($testimonial->testimonial_text, 100) }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td>
                                    <span class="badge bg-{{ $testimonial->is_approved ? 'success' : 'warning' }}">
                                        {{ $testimonial->is_approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $testimonial->is_featured ? 'primary' : 'secondary' }}">
                                        {{ $testimonial->is_featured ? 'Featured' : 'Normal' }}
                                    </span>
                                </td>
                                <td>{{ $testimonial->published_date?->format('Y-m-d') ?? '-' }}</td>
                                <td>
                                    <button wire:click="edit({{ $testimonial->id }})" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $testimonial->id }})" wire:confirm="Are you sure?" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No testimonials found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $testimonials->links() }}
            </div>
        </div>
    </div>

    @if($showForm)
    <div class="modal d-block" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'Edit' : 'Add' }} Testimonial</h5>
                    <button wire:click="showForm = false" class="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Author Name *</label>
                        <input type="text" wire:model="authorName" class="form-control">
                        @error('authorName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author Title</label>
                        <input type="text" wire:model="authorTitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Testimonial Text *</label>
                        <textarea wire:model="testimonialText" class="form-control" rows="4"></textarea>
                        @error('testimonialText') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rating *</label>
                        <select wire:model="rating" class="form-control">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Published Date</label>
                        <input type="date" wire:model="publishedDate" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author Image</label>
                        <input type="file" wire:model="authorImage" class="form-control" accept="image/*">
                        @if($authorImage)
                            <img src="{{ $authorImage->temporaryUrl() }}" class="mt-2" style="max-height: 100px;">
                        @endif
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" wire:model="isApproved" class="form-check-input" id="isApproved">
                            <label class="form-check-label" for="isApproved">Approved</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" wire:model="isFeatured" class="form-check-input" id="isFeatured">
                            <label class="form-check-label" for="isFeatured">Featured</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="showForm = false" class="btn btn-secondary">Cancel</button>
                    <button wire:click="save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
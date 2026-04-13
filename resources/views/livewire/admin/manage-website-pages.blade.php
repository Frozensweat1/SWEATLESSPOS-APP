<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Manage Website Pages</h1>
        </div>
    </div>

    <!-- Add/Edit Form -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ $editingMode ? 'Edit Page' : 'Create New Page' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label class="form-label">Page Title *</label>
                            <input type="text" wire:model="title"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="e.g., Home, About Us">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Page Slug *</label>
                            <input type="text" wire:model="slug"
                                class="form-control @error('slug') is-invalid @enderror"
                                placeholder="e.g., home, about-us">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea wire:model="content" class="form-control" rows="6" placeholder="Page content (HTML supported)"></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" wire:model="meta_title" class="form-control"
                                    placeholder="SEO meta title">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meta Description</label>
                                <input type="text" wire:model="meta_description" class="form-control"
                                    placeholder="SEO meta description">
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" wire:model="is_active" id="isActive" class="form-check-input">
                            <label class="form-check-label" for="isActive">
                                Active
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:loading.class="opacity-50" wire:target="save">
                            <span wire:loading.remove wire:target="save">{{ $editingMode ? 'Update' : 'Create' }}
                                Page</span>
                            <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                        @if ($editingMode)
                            <button type="button" wire:click="resetForm" class="btn btn-secondary"
                                wire:loading.attr="disabled" wire:target="resetForm">
                                <span wire:loading.remove wire:target="resetForm">Cancel</span>
                                <span wire:loading wire:target="resetForm"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Pages</h5>
                        </div>
                        <div class="col-auto">
                            <input type="text" wire:model.live="search" class="form-control"
                                placeholder="Search pages..." style="width: 300px;">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages as $page)
                                <tr>
                                    <td>
                                        <strong>{{ $page->title }}</strong>
                                    </td>
                                    <td>
                                        <code>/{{ $page->slug }}</code>
                                    </td>
                                    <td>
                                        @if ($page->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $page->creator->name ?? 'N/A' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $page->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <button wire:click="edit({{ $page->id }})" wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50" wire:target="edit({{ $page->id }})"
                                            class="btn btn-sm btn-warning">
                                            <span wire:loading.remove
                                                wire:target="edit({{ $page->id }})">Edit</span>
                                            <span wire:loading wire:target="edit({{ $page->id }})"><i
                                                    class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                        <button wire:click="delete({{ $page->id }})"
                                            wire:confirm="Delete this page?" wire:loading.attr="disabled"
                                            wire:loading.class="opacity-50" wire:target="delete({{ $page->id }})"
                                            class="btn btn-sm btn-danger">
                                            <span wire:loading.remove
                                                wire:target="delete({{ $page->id }})">Delete</span>
                                            <span wire:loading wire:target="delete({{ $page->id }})"><i
                                                    class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No pages found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $pages->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

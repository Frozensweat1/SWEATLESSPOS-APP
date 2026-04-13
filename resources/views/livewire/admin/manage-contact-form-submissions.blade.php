<div class="container py-4">
    <h2 class="mb-4">Contact Form Submissions</h2>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-end gap-2">
                <div class="col-md">
                    <input type="text" wire:model.live="search" placeholder="Search by name, email..."
                        class="form-control">
                </div>
                <div class="col-md-auto">
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="all">All</option>
                        <option value="new">New</option>
                        <option value="read">Read</option>
                        <option value="responded">Responded</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                    <tr class="{{ $submission->status === 'new' ? 'table-warning' : '' }}">
                        <td><strong>{{ $submission->name }}</strong></td>
                        <td>{{ $submission->email }}</td>
                        <td>{{ Str::limit($submission->subject, 30) }}</td>
                        <td><span
                                class="badge bg-{{ $submission->status === 'new' ? 'danger' : ($submission->status === 'responded' ? 'success' : 'info') }}">{{ ucfirst($submission->status) }}</span>
                        </td>
                        <td>{{ $submission->created_at->format('M d, Y') }}</td>
                        <td>
                            <button wire:click="viewSubmission({{ $submission->id }})"
                                class="btn btn-sm btn-primary">View</button>
                            <button wire:click="deleteSubmission({{ $submission->id }})"
                                wire:confirm="Delete this submission?" class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No submissions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $submissions->links() }}
    </div>

    @if ($selectedSubmission)
        <div class="modal d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $selectedSubmission->name }}</h5>
                        <button type="button" wire:click="closeSubmission" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Email:</strong> {{ $selectedSubmission->email }}</p>
                        <p><strong>Phone:</strong> {{ $selectedSubmission->phone ?? 'N/A' }}</p>
                        <p><strong>Subject:</strong> {{ $selectedSubmission->subject }}</p>
                        <hr>
                        <p><strong>Message:</strong></p>
                        <p>{{ $selectedSubmission->message }}</p>
                        <hr>

                        @if ($selectedSubmission->status !== 'responded')
                            <form wire:submit="respondToSubmission">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Your Response</label>
                                    <textarea wire:model="responseMessage" class="form-control @error('responseMessage') is-invalid @enderror"
                                        rows="4" placeholder="Your response..."></textarea>
                                    @error('responseMessage')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Send Response</button>
                            </form>
                        @else
                            <div class="alert alert-success">
                                <strong>Response Sent:</strong><br>
                                {{ $selectedSubmission->admin_response }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

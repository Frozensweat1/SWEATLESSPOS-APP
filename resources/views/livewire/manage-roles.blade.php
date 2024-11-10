<div>
    {{-- The whole world belongs to you. --}}
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Manage User Roles</h5>
            </div>
            <div class="card-body">
                <!-- Success Message -->
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Form to Assign Role -->
                <form wire:submit.prevent="assignRole">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="userSelect" class="form-label">Select User</label>
                            <div x-data="{ selectedUserId: @entangle('selectedUser') }">
                                <!-- userSelect Selection Dropdown -->
                                <div class="mb-3" wire:ignore>
                                    <select x-model="selectedUserId" class="form-select js-choice" id="userSelect"
                                        x-init="new Choices($el);
                                        $el.addEventListener('change', function(event) {
                                            selectedUserId = event.target.value;
                                            @this.set('selectedUser', event.target.value, true); // Update with debounce to prevent immediate re-render
                                        });" size="1" name="user">
                                        <option value="">Select User</option>
                                        @foreach ($selectusers as $selectuser)
                                            <option value="{{ $selectuser->id }}">{{ $selectuser->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            @error('selectedUser')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="roleSelect" class="form-label">Select Role</label>
                            <select wire:model="selectedRole" id="roleSelect" class="form-select">
                                <option value="">Choose a role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" wire:target="assignRole"
                        class="btn btn-outline-primary btn-pill d-flex align-items-center float-end"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                        <!-- Spinner -->
                        <span wire:loading wire:target="assignRole" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>

                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline"><i class="fas fa-recycle"></i>
                            <span wire:loading.remove wire:target="assignRole">Assign
                                Role</span>
                        </span>
                        <span wire:loading wire:target="assignRole">Assigning...</span>
                        <span class="d-md-none">
                            <i class="fas fa-recycle"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Display Assigned Roles -->
        <div class="mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Users and Their Roles</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge bg-secondary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <link href="{{ asset('vendors/choices/choices.min.css') }}" rel="stylesheet" />
    @endpush
    @push('js')
        <!-- Choice.js -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @endpush
</div>

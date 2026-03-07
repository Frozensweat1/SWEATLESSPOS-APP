<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class ManageUser extends Component
{
    use WithPagination;

    public $searchTerm = '';  // For searching users
    public $roleFilter = '';  // For filtering users by role

    protected $paginationTheme = 'bootstrap'; // Using Bootstrap for pagination UI
    public $selected_id;

    public function updatingSearchTerm()
    {
        $this->resetPage(); // Reset to the first page on search
    }

    public function updatingRoleFilter()
    {
        $this->resetPage(); // Reset to the first page on filtering
    }

    // Delete User Function
    public function Delete($id)
    {
        $this->selected_id = $id;
        LivewireAlert::title('Are you sure you want to delete?')
            ->warning()
            ->onConfirm('confirmedDeletion')
            ->withConfirmButton('Yes, Delete')
            ->withCancelButton('Cancel')
            ->show();
    }

    public function confirmedDeletion()
    {
        $user = User::find($this->selected_id);

        if ($user) {
            $authUser = Auth::user();

            Notification::create([
                'user_id' => $authUser->id,
                'title' => 'User Deleted',
                'message' => "User '{$user->name}' (ID: {$user->id}) was deleted by {$authUser->name} (ID: {$authUser->id}).",
                'type' => 'warning',
                'is_read' => false,
            ]);

            $user->delete();
            LivewireAlert::title('Deleted')
                ->text('Successfully Deleted ' . $user->name)
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } else {
            LivewireAlert::title('Error')
                ->text('Unable to delete supplier record.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
        }
    }

    // Export Users Functionality
    public function exportUsers()
    {
        $fileName = 'users_export.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $usersQuery = User::query();

        // Apply filters if necessary
        if ($this->searchTerm) {
            $usersQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        if ($this->roleFilter) {
            $usersQuery->role($this->roleFilter);
        }

        $users = $usersQuery->get();

        // Streamed Response for large data sets
        return new StreamedResponse(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Created At']);

            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->join(', ');
                fputcsv($handle, [$user->id, $user->name, $user->email, $roles, $user->created_at]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    protected $listeners = [
        'confirmedDeletion'
    ];

    public function render()
    {
        // Fetch users excluding super-admin
        $usersQuery = User::query()
            ->whereNotIn('id', function ($query) {
                $query->select('model_id')
                    ->from('model_has_roles')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where('roles.name', 'super-admin');
            });

        // Apply search filter
        if ($this->searchTerm) {
            $usersQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Apply role-based filtering
        if ($this->roleFilter) {
            $usersQuery->role($this->roleFilter);
        }

        // Paginate the results
        $users = $usersQuery->paginate(10);

        // Fetch all roles for the dropdown
        $roles = Role::all();
        return view('livewire.manage-user', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}

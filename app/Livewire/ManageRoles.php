<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ManageRoles extends Component
{
    use WithFileUploads, LivewireAlert, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $roles;
    public $selectedUser = null;
    public $selectedRole = null;
    public $selectusers;

    protected $rules = [
        'selectedUser' => 'required|exists:users,id',
        'selectedRole' => 'required|exists:roles,name',
    ];

    public function mount()
    {
        $this->selectusers = User::get();
        $this->roles = Role::all();
    }


    public function assignRole()
    {
        $this->validate();

        $user = User::findOrFail($this->selectedUser);
        $user->syncRoles([$this->selectedRole]);

        $this->reset(['selectedUser', 'selectedRole']);
        session()->flash('message', 'Role assigned successfully.');
    }

    public function render()
    {
        // Fetch paginated users with roles relationship
        $users = User::with('roles')->paginate(10);

        return view('livewire.manage-roles', [
            'users' => $users, // Pass paginated users to the view
        ]);
    }
}

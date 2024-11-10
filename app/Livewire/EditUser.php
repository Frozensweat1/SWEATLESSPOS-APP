<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Layout('layouts.app')]
class EditUser extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    public $userId;
    public $name;
    public $email;
    public $role;
    public $image;
    public $existingImage;

    public $roles;

    public function mount($id)
    {
        $this->userId = $id;
        $user = User::findOrFail($this->userId);  // Load the user by ID

        // Prepopulate the form with existing user data
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()->name ?? ''; // Get the first assigned role
        $this->existingImage = $user->profile_photo_path; // Store the path of the current image
        $this->roles = Role::all(); // Fetch available roles
    }

    // Validation rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId, // Ignore the current user’s email during validation
            'role' => 'required|exists:roles,name',
            'image' => 'nullable|image|max:1024', // Optional image (1MB max)
        ];
    }

    // Method to update the user

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        // Update user data
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Handle image upload if provided
        if ($this->image) {
            $imagePath = $this->image->store('user_images', 'public');
            $user->update(['profile_photo_path' => $imagePath]);
        }

        // Update the role
        $user->syncRoles([$this->role]); // Sync the selected role

        // Capture details about the user performing the update
        $authUser = Auth::user();

        // Create a notification for the update event
        Notification::create([
            'user_id' => $authUser->id,  // Authenticated user performing the update
            'title' => 'User Updated',
            'message' => "User '{$user->name}' (ID: {$user->id}) was updated by {$authUser->name} (ID: {$authUser->id}).",
            'type' => 'info',
            'is_read' => false,
        ]);
        $this->alert('success', 'Succesfully updated ' . $user->name);
    }

    public function render()
    {
        $availableRoles = Role::all();
        return view('livewire.edit-user', ['availableRoles' => $availableRoles]);
    }
}

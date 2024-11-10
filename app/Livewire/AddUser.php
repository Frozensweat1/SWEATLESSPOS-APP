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

#[Layout('layouts.app')] // This is how layouts are defined in Livewire 3
class AddUser extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    public $name;
    public $email;
    public $password;
    public $role;
    public $image;

    public $roles; // To store available roles for the dropdown

    public function mount()
    {
        // Fetch available roles when the component is mounted
        $this->roles = Role::all();
    }

    // Validation rules in a separate method
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'image' => 'nullable|image|max:5024', // Optional image, max size 1MB
        ];
    }

    // Method to create a new user
    public function createUser()
    {
        try {
            // Validate the input based on the defined rules
            $this->validate($this->rules());
            // Create the user in the database (excluding the profile photo initially)
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Handle image upload, if provided
            if ($this->image) {
                // Store the image in the 'user_images' directory within the 'public' disk
                $imagePath = $this->image->store('user_images', 'public');

                // Update the user with the image path
                $user->update(['profile_photo_path' => $imagePath]);
            }

            // Assign the selected role to the user
            $user->assignRole($this->role);

            // Capture details about the authenticated user creating the new user
            $authUser = Auth::user();

            // Create a notification for the user creation event
            Notification::create([
                'user_id' => $authUser->id, // Authenticated user creating the new user
                'title' => 'New User Created',
                'message' => "User '{$user->name}' (ID: {$user->id}) was created by {$authUser->name} (ID: {$authUser->id}).",
                'type' => 'success',
                'is_read' => false,
            ]);

            // Reset form fields after successful creation
            $this->reset(['name', 'email', 'password', 'role', 'image']);

            // Show a success message
            $this->alert('success', 'Successfully created ' . $user->name);
        } catch (\Exception $e) {
            $this->alert('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.add-user');
    }
}

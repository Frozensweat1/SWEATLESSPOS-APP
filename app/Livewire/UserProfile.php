<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $profile_photo;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|min:6|confirmed',
            'profile_photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $user = Auth::user();

        $dataToUpdate = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        $passwordChanged = false;

        if ($this->password) {
            $dataToUpdate['password'] = Hash::make($this->password);
            $passwordChanged = true;
        }

        if ($this->profile_photo) {
            // Delete the old profile image if it exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store the new profile image
            $dataToUpdate['profile_photo_path'] = $this->profile_photo->store('users', 'public');
        }

        // Update user attributes in the database
        $user->update($dataToUpdate);

        // Flash a success message
        session()->flash('message', 'Profile updated successfully.');

        // If the password was changed, log out the user
        if ($passwordChanged) {
            Auth::guard('web')->logout(); // Ensure session guard is used for logout
            return redirect()->route('login')->with('message', 'Password updated. Please log in again.');
        }
    }
    public function render()
    {
        return view('livewire.user-profile');
    }
}

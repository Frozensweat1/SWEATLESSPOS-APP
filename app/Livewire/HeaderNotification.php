<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class HeaderNotification extends Component
{
    public $notifications;

    public function mount()
    {
        // Fetch notifications and mark as unread (for display purposes)
        $this->notifications = Notification::with('user')
            ->latest()
            ->limit(10) // Limit to 10 notifications
            ->get();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        $this->mount(); // Refresh notifications list
    }

    public function render()
    {
        return view('livewire.header-notification');
    }
}

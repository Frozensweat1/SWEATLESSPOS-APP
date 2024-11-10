<?php

namespace App\Livewire;

use Livewire\Component;

class ViewNotification extends Component
{
    public function render()
    {
        return view('livewire.view-notification')->layout('layouts.app');
    }
}

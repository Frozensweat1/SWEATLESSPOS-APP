<?php

namespace App\Livewire;

use Livewire\Component;

class SystemSettings extends Component
{
    public function render()
    {
        return view('livewire.system-settings')->layout('layouts.app');
    }
}

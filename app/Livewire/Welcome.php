<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting; // Import your Setting model

class Welcome extends Component
{
    public function render()
    {
        // Fetch settings records
        $settings = Setting::first();

        return view('livewire.welcome', [
            'settings' => $settings,
        ]);
    }
}

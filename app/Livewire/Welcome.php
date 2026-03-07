<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting; // Import your Setting model

class Welcome extends Component
{
    public function render()
    {
        // Fetch settings records or create default
        $settings = Setting::first() ?? new Setting([
            'site_name' => config('app.name', 'Sweatless POS'),
            'currency' => 'USD',
            'contact_phone' => '',
            'contact_email' => '',
            'address' => '',
            'receipt_header' => 'In God We Trust!',
        ]);

        return view('livewire.welcome', [
            'settings' => $settings,
        ]);
    }
}

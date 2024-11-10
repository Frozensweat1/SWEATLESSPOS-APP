<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class Settings extends Component
{
    use WithFileUploads;

    // General settings attributes
    public $site_name, $currency, $tax_rate, $contact_email, $contact_phone, $address;
    public $receipt_header, $receipt_footer, $invoice_prefix, $auto_print_receipt;
    public $theme_color, $currency_symbol, $enable_two_factor_auth, $require_password_change;
    public $track_inventory, $low_stock_threshold, $enable_expiry_tracking;

    // File upload attributes
    public $logo_new, $favicon_new, $background_img1_new, $background_img2_new;

    // Existing file paths
    public $existing_logo, $existing_favicon, $existing_background_img1, $existing_background_img2;

    public function mount()
    {
        $setting = Setting::first();
        if ($setting) {
            $this->fill($setting->toArray());
            $this->existing_logo = $setting->logo;
            $this->existing_favicon = $setting->favicon;
            $this->existing_background_img1 = $setting->background_img1;
            $this->existing_background_img2 = $setting->background_img2;
        }
    }

    public function save()
    {
        // Validate the input data
        $validatedData = $this->validate([
            'site_name' => 'nullable|string',
            'currency' => 'nullable|string',
            'tax_rate' => 'nullable|numeric',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'receipt_header' => 'nullable|string',
            'receipt_footer' => 'nullable|string',
            'invoice_prefix' => 'nullable|string',
            'auto_print_receipt' => 'boolean',
            'theme_color' => 'nullable|string',
            'currency_symbol' => 'nullable|string',
            'enable_two_factor_auth' => 'boolean',
            'require_password_change' => 'boolean',
            'track_inventory' => 'boolean',
            'low_stock_threshold' => 'integer|min:0',
            'enable_expiry_tracking' => 'boolean',
            'logo_new' => 'nullable|image|max:1024', // Max 1MB
            'favicon_new' => 'nullable|image|max:512', // Max 512KB
            'background_img1_new' => 'nullable|image|max:2048', // Max 2MB
            'background_img2_new' => 'nullable|image|max:2048', // Max 2MB
        ]);

        try {
            // Handle file uploads and update paths if new files are uploaded
            if ($this->logo_new) {
                $this->deleteExistingFile($this->existing_logo);
                $validatedData['logo'] = $this->logo_new->store('logos', 'public');
            }
            if ($this->favicon_new) {
                $this->deleteExistingFile($this->existing_favicon);
                $validatedData['favicon'] = $this->favicon_new->store('favicons', 'public');
            }
            if ($this->background_img1_new) {
                $this->deleteExistingFile($this->existing_background_img1);
                $validatedData['background_img1'] = $this->background_img1_new->store('backgrounds', 'public');
            }
            if ($this->background_img2_new) {
                $this->deleteExistingFile($this->existing_background_img2);
                $validatedData['background_img2'] = $this->background_img2_new->store('backgrounds', 'public');
            }

            // Save or update the settings record
            $setting = Setting::first(); // fetch the first (and only) settings record
            if ($setting) {
                $setting->update($validatedData); // update if exists
            } else {
                Setting::create($validatedData); // create if no record exists
            }

            session()->flash('message', 'Settings updated successfully.');

            // Refresh component state to reflect the changes
            $this->mount();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating settings: ' . $e->getMessage());
        }
    }

    // Delete an existing file if it exists in storage
    private function deleteExistingFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    public function render()
    {
        return view('livewire.settings');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

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
            'site_name' => 'nullable|string|max:255',
            'currency' => 'nullable|string|in:USD,EUR,GHS',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'receipt_header' => 'nullable|string|max:255',
            'receipt_footer' => 'nullable|string|max:255',
            'invoice_prefix' => 'nullable|string|max:20',
            'auto_print_receipt' => 'boolean',
            'theme_color' => 'nullable|string|in:default,dark,light',
            'currency_symbol' => 'nullable|string|max:5',
            'enable_two_factor_auth' => 'boolean',
            'require_password_change' => 'boolean',
            'track_inventory' => 'boolean',
            'low_stock_threshold' => 'integer|min:0',
            'enable_expiry_tracking' => 'boolean',
            'logo_new' => 'nullable|image|max:3024',
            'favicon_new' => 'nullable|image|max:2048',
            'background_img1_new' => 'nullable|image|max:3048',
            'background_img2_new' => 'nullable|image|max:3048',
        ]);

        try {
            // Handle file uploads and update paths if new files are uploaded
            if ($this->logo_new) {
                $this->deleteExistingFile($this->existing_logo);
                $validatedData['logo'] = $this->logo_new->store('logos', 'public');
                $this->existing_logo = $validatedData['logo'];
            }
            if ($this->favicon_new) {
                $this->deleteExistingFile($this->existing_favicon);
                $validatedData['favicon'] = $this->favicon_new->store('favicons', 'public');
                $this->existing_favicon = $validatedData['favicon'];
            }
            if ($this->background_img1_new) {
                $this->deleteExistingFile($this->existing_background_img1);
                $validatedData['background_img1'] = $this->background_img1_new->store('backgrounds', 'public');
                $this->existing_background_img1 = $validatedData['background_img1'];
            }
            if ($this->background_img2_new) {
                $this->deleteExistingFile($this->existing_background_img2);
                $validatedData['background_img2'] = $this->background_img2_new->store('backgrounds', 'public');
                $this->existing_background_img2 = $validatedData['background_img2'];
            }

            // Save or update the settings record
            $setting = Setting::first();

            if ($setting) {
                $setting->update($validatedData);
            } else {
                Setting::create($validatedData);
            }

            // Clear file inputs after successful save
            $this->reset(['logo_new', 'favicon_new', 'background_img1_new', 'background_img2_new']);

            LivewireAlert::title('Success')
                ->text('Settings updated successfully.')
                ->success()
                ->toast()
                ->position('top-end')
                ->show();
        } catch (\Exception $e) {
            LivewireAlert::title('Error')
                ->text('An error occurred while updating settings. Please try again.')
                ->error()
                ->toast()
                ->position('top-end')
                ->show();
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

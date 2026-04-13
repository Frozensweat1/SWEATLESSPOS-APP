<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Layout('layouts.app')]
class ManageWebsiteSettings extends Component
{
    use WithFileUploads;

    public $settings = [
        'site_name' => '',
        'site_tagline' => '',
        'phone' => '',
        'email' => '',
        'address' => '',
        'map_url' => '',
        'facebook_url' => '',
        'instagram_url' => '',
        'twitter_url' => '',
        'linkedin_url' => '',
        'tiktok_url' => '',
        'founder_name' => '',
        'founder_title' => '',
        'founder_bio' => '',
    ];

    public $heroBackgroundImage = null;
    public $aboutBackgroundImage = null;
    public $productsBackgroundImage = null;
    public $galleryBackgroundImage = null;
    public $contactBackgroundImage = null;
    public $logoImage = null;
    public $homeDescriptionImage = null;
    public $founderImage = null;

    public $heroBackgroundPreview = null;
    public $aboutBackgroundPreview = null;
    public $productsBackgroundPreview = null;
    public $galleryBackgroundPreview = null;
    public $contactBackgroundPreview = null;
    public $logoPreview = null;
    public $homeDescriptionPreview = null;
    public $founderImagePreview = null;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        foreach (array_keys($this->settings) as $key) {
            $this->settings[$key] = WebsiteSetting::get($key, '');
        }

        // Load background image previews
        $this->heroBackgroundPreview = WebsiteSetting::get('hero_background_image');
        $this->aboutBackgroundPreview = WebsiteSetting::get('about_background_image');
        $this->productsBackgroundPreview = WebsiteSetting::get('products_background_image');
        $this->galleryBackgroundPreview = WebsiteSetting::get('gallery_background_image');
        $this->contactBackgroundPreview = WebsiteSetting::get('contact_background_image');
        $this->logoPreview = WebsiteSetting::get('logo_image');
        $this->homeDescriptionPreview = WebsiteSetting::get('home_description_image');
        $this->founderImagePreview = WebsiteSetting::get('founder_image');
    }

    public function saveSettings()
    {
        $this->validate([
            'settings.site_name' => 'required|string|max:255',
            'settings.site_tagline' => 'nullable|string|max:255',
            'settings.phone' => 'nullable|string|max:20',
            'settings.email' => 'nullable|email',
            'settings.address' => 'nullable|string|max:500',
            'settings.map_url' => 'nullable|string|max:2000',
            'settings.facebook_url' => 'nullable|url',
            'settings.instagram_url' => 'nullable|url',
            'settings.twitter_url' => 'nullable|url',
            'settings.linkedin_url' => 'nullable|url',
            'settings.tiktok_url' => 'nullable|url',
            'heroBackgroundImage' => 'nullable|image|max:5120',
            'aboutBackgroundImage' => 'nullable|image|max:5120',
            'productsBackgroundImage' => 'nullable|image|max:5120',
            'galleryBackgroundImage' => 'nullable|image|max:5120',
            'contactBackgroundImage' => 'nullable|image|max:5120',
            'logoImage' => 'nullable|image|max:2048',
            'homeDescriptionImage' => 'nullable|image|max:5120',
            'founderImage' => 'nullable|image|max:5120',
        ]);

        foreach ($this->settings as $key => $value) {
            WebsiteSetting::set($key, $value);
        }

        // Handle background image uploads
        if ($this->heroBackgroundImage) {
            $path = $this->heroBackgroundImage->store('hero-background', 'public');
            WebsiteSetting::set('hero_background_image', $path);
        }

        if ($this->aboutBackgroundImage) {
            $path = $this->aboutBackgroundImage->store('about-background', 'public');
            WebsiteSetting::set('about_background_image', $path);
        }

        if ($this->productsBackgroundImage) {
            $path = $this->productsBackgroundImage->store('products-background', 'public');
            WebsiteSetting::set('products_background_image', $path);
        }

        if ($this->galleryBackgroundImage) {
            $path = $this->galleryBackgroundImage->store('gallery-background', 'public');
            WebsiteSetting::set('gallery_background_image', $path);
        }

        if ($this->contactBackgroundImage) {
            $path = $this->contactBackgroundImage->store('contact-background', 'public');
            WebsiteSetting::set('contact_background_image', $path);
        }

        if ($this->logoImage) {
            $path = $this->logoImage->store('logo', 'public');
            WebsiteSetting::set('logo_image', $path);
        }

        if ($this->homeDescriptionImage) {
            $path = $this->homeDescriptionImage->store('home-description', 'public');
            WebsiteSetting::set('home_description_image', $path);
        }

        if ($this->founderImage) {
            $path = $this->founderImage->store('founder', 'public');
            WebsiteSetting::set('founder_image', $path);
        }

        LivewireAlert::title('Success')
            ->text('Website settings updated successfully.')
            ->success()
            ->toast()
            ->show();

        // Reload settings to get updated previews
        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.admin.manage-website-settings');
    }
}

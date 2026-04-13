<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use App\Helpers\MapHelper;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class Contact extends Component
{
    public $page;
    public $address;
    public $phone;
    public $email;
    public $latitude;
    public $longitude;
    public $mapsUrl;
    public $heroBackgroundUrl = '';

    public function mount()
    {
        try {
            $this->page = WebsitePage::where('slug', 'contact')->firstOrFail();
        } catch (\Exception $e) {
            $this->page = new WebsitePage([
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Get in Touch</h1><p>Have questions? We would love to hear from you. Send us a message!</p>',
            ]);
        }

        // Load contact details from WebsiteSettings
        $location = MapHelper::getStoreLocation();
        $this->address = $location['address'];
        $this->phone = $location['phone'];
        $this->email = $location['email'];
        $this->latitude = $location['latitude'];
        $this->longitude = $location['longitude'];

        // Generate Google Maps URL using helper
        $this->mapsUrl = MapHelper::getStoreMapUrl();

        // Build background URL
        $contactBackground = WebsiteSetting::get('contact_background_image');
        $this->heroBackgroundUrl = $contactBackground
            ? 'url(' . asset('storage/' . $contactBackground) . ')'
            : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
    }

    public function render()
    {
        return view('livewire.website.contact');
    }
}

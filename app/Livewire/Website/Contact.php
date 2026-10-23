<?php

namespace App\Livewire\Website;

use App\Helpers\MapHelper;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class Contact extends Component
{
    public function render()
    {
        $page = WebsitePage::where('slug', 'contact')->where('is_active', true)->first();
        $location = MapHelper::getStoreLocation();
        $hours = WebsiteSetting::get('business_hours');

        return view('livewire.website.contact', [
            'page' => $page,
            'address' => $location['address'] ?? null,
            'phone' => $location['phone'] ?? null,
            'email' => $location['email'] ?? null,
            'mapsUrl' => MapHelper::getStoreMapUrl(),
            'businessHours' => is_array($hours) ? $hours : [],
            'whatsapp' => preg_replace('/\D+/', '', (string) WebsiteSetting::get('whatsapp_number', '')),
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('contact_background_image', 'linear-gradient(135deg, #ec4899, #f59e0b)'),
        ])->layoutData([
            'title' => $page?->meta_title ?: 'Contact Us',
            'description' => $page?->meta_description ?: 'Get in touch with us.',
        ]);
    }
}

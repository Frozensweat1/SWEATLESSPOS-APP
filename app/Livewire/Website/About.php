<?php

namespace App\Livewire\Website;

use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class About extends Component
{
    public function render()
    {
        $page = WebsitePage::where('slug', 'about')->where('is_active', true)->first();

        return view('livewire.website.about', [
            'page' => $page,
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('about_background_image'),
            'founderName' => WebsiteSetting::get('founder_name', ''),
            'founderTitle' => WebsiteSetting::get('founder_title', ''),
            'founderBio' => WebsiteSetting::get('founder_bio', ''),
            'founderImage' => WebsiteSetting::get('founder_image', ''),
        ])->layoutData([
            'title' => $page?->meta_title ?: 'About Us',
            'description' => $page?->meta_description ?: ($page ? strip_tags($page->content) : null),
        ]);
    }
}

<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class About extends Component
{
    public $page;
    public $heroBackgroundUrl = '';
    public $founderName = '';
    public $founderTitle = '';
    public $founderBio = '';
    public $founderImage = '';

    public function mount()
    {
        try {
            $this->page = WebsitePage::findBySlugOrFail('about');
        } catch (\Exception $e) {
            $this->page = new WebsitePage([
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About Our Store</h1><p>Learn more about us...</p>',
            ]);
        }

        // Build background URL
        $aboutBackground = WebsiteSetting::get('about_background_image');
        $this->heroBackgroundUrl = $aboutBackground
            ? 'url(' . asset('storage/' . $aboutBackground) . ')'
            : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';

        // Load founder information from settings
        $this->founderName = WebsiteSetting::get('founder_name', '');
        $this->founderTitle = WebsiteSetting::get('founder_title', '');
        $this->founderBio = WebsiteSetting::get('founder_bio', '');
        $this->founderImage = WebsiteSetting::get('founder_image', '');
    }

    public function render()
    {
        return view('livewire.website.about');
    }
}

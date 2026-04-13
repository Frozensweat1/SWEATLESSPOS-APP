<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\Testimonial;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class Testimonials extends Component
{
    public $page;
    public $testimonials = [];
    public $heroBackgroundUrl = '';

    public function mount()
    {
        try {
            $this->page = WebsitePage::findBySlugOrFail('testimonials');
        } catch (\Exception $e) {
            $this->page = new WebsitePage([
                'title' => 'Customer Testimonials',
                'slug' => 'testimonials',
                'content' => '<p>Read what our satisfied customers have to say about our products and services.</p>',
            ]);
        }

        // Load all approved and published testimonials
        $this->testimonials = Testimonial::approved()
            ->published()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Load website settings for hero background
        $backgroundImage = WebsiteSetting::get('testimonials_background_image');
        $this->heroBackgroundUrl = $backgroundImage
            ? 'url(' . asset('storage/' . $backgroundImage) . ')'
            : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }

    public function render()
    {
        return view('livewire.website.testimonials');
    }
}

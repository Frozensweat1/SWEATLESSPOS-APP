<?php

namespace App\Livewire\Website;

use App\Models\Testimonial;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class Testimonials extends Component
{
    public function render()
    {
        $page = WebsitePage::where('slug', 'testimonials')->where('is_active', true)->first();

        return view('livewire.website.testimonials', [
            'page' => $page,
            'testimonials' => Testimonial::approved()->published()
                ->orderByDesc('is_featured')->orderByDesc('created_at')->get(),
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('testimonials_background_image'),
        ])->layoutData([
            'title' => $page?->meta_title ?: 'Customer Reviews',
            'description' => $page?->meta_description ?: 'Read what our customers say about us.',
        ]);
    }
}

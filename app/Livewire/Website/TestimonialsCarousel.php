<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class TestimonialsCarousel extends Component
{
    public $testimonials;
    public $currentIndex = 0;

    public function mount()
    {
        $this->testimonials = Testimonial::approved()
            ->published()
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        \App\Models\WebsitePageView::create([
            'page_slug' => 'testimonials',
            'page_title' => 'Testimonials',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
        ]);
    }

    public function next()
    {
        $this->currentIndex = ($this->currentIndex + 1) % count($this->testimonials);
    }

    public function prev()
    {
        $this->currentIndex = ($this->currentIndex - 1 + count($this->testimonials)) % count($this->testimonials);
    }

    public function render()
    {
        return view('livewire.website.testimonials-carousel');
    }
}

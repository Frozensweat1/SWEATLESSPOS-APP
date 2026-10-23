<?php

namespace App\Livewire\Website;

use App\Models\Testimonial;
use Livewire\Component;

// Rendering is client-side scroll-snap; no Livewire round trips or page-view writes.
class TestimonialsCarousel extends Component
{
    public function render()
    {
        return view('livewire.website.testimonials-carousel', [
            'testimonials' => Testimonial::approved()->published()
                ->orderByDesc('is_featured')->orderByDesc('created_at')->limit(10)->get(),
        ]);
    }
}

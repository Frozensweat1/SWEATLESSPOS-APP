<?php

namespace App\Livewire\Website;

use App\Models\GalleryAlbum;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class GalleryDetail extends Component
{
    public string $slug;

    public function mount($slug): void
    {
        $this->slug = GalleryAlbum::active()->bySlug($slug)->firstOrFail()->slug;
    }

    public function render()
    {
        $album = GalleryAlbum::active()->bySlug($this->slug)->with('activeImages')->firstOrFail();

        return view('livewire.website.gallery-detail', [
            'album' => $album,
            'images' => $album->activeImages,
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('gallery_background_image', 'linear-gradient(135deg, #0ea5e9, #6366f1)'),
        ])->layoutData([
            'title' => $album->name,
            'description' => $album->description ?: 'Photos from ' . $album->name,
            'image' => $album->cover_image ? asset('storage/' . $album->cover_image) : null,
        ]);
    }
}

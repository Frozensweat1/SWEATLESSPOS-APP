<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class GalleryDetail extends Component
{
    public $album = null;
    public $images = [];
    public $heroBackgroundUrl = '';
    public $viewingImage = null;

    public function mount($slug)
    {
        $this->album = GalleryAlbum::with('activeImages')->bySlug($slug)->firstOrFail();
        $this->images = $this->album->activeImages()->get();

        // Build background URL
        $galleryBackground = WebsiteSetting::get('gallery_background_image');
        $this->heroBackgroundUrl = $galleryBackground
            ? 'url(' . asset('storage/' . $galleryBackground) . ')'
            : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
    }

    public function viewImage($imageId)
    {
        $this->viewingImage = GalleryImage::find($imageId);
    }

    public function closeImageModal()
    {
        $this->viewingImage = null;
    }

    public function render()
    {
        return view('livewire.website.gallery-detail');
    }
}
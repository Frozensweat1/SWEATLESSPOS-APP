<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\GalleryAlbum;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class Gallery extends Component
{
    public $albums = [];
    public $selectedAlbum = null;
    public $selectedImages = [];
    public $heroBackgroundUrl = '';

    public function mount()
    {
        $this->loadAlbums();

        // Build background URL
        $galleryBackground = WebsiteSetting::get('gallery_background_image');
        $this->heroBackgroundUrl = $galleryBackground
            ? 'url(' . asset('storage/' . $galleryBackground) . ')'
            : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
    }

    public function loadAlbums()
    {
        $this->albums = GalleryAlbum::active()->ordered()->with('activeImages')->get();
    }

    public function selectAlbum($albumId)
    {
        $this->selectedAlbum = GalleryAlbum::with('activeImages')->find($albumId);
        $this->selectedImages = $this->selectedAlbum->activeImages()->get();
    }

    public function clearSelection()
    {
        $this->selectedAlbum = null;
        $this->selectedImages = [];
    }

    public function render()
    {
        return view('livewire.website.gallery', [
            'albums' => $this->albums,
        ]);
    }
}

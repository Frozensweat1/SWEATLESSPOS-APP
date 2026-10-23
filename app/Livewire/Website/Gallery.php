<?php

namespace App\Livewire\Website;

use App\Models\GalleryAlbum;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class Gallery extends Component
{
    public function render()
    {
        return view('livewire.website.gallery', [
            'albums' => GalleryAlbum::active()->ordered()
                ->with('activeImages:id,gallery_album_id,image_path,display_order')
                ->get(),
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('gallery_background_image', 'linear-gradient(135deg, #0ea5e9, #6366f1)'),
        ])->layoutData([
            'title' => 'Gallery',
            'description' => 'Photo albums and moments from our business.',
        ]);
    }
}

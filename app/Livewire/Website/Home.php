<?php

namespace App\Livewire\Website;

use App\Models\GalleryAlbum;
use App\Models\Product;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.website')]
class Home extends Component
{
    public function render()
    {
        $page = WebsitePage::where('slug', 'home')->where('is_active', true)->first();
        $about = WebsitePage::where('slug', 'about')->where('is_active', true)->first();

        $siteName = WebsiteSetting::get('site_name', 'Sweatless POS');
        $tagline = WebsiteSetting::get('site_tagline', 'Your trusted business partner');

        return view('livewire.website.home', [
            'page' => $page,
            'aboutPage' => $about,
            'siteName' => $siteName,
            'tagline' => $tagline,
            'heroBackgroundUrl' => WebsiteSetting::backgroundCss('hero_background_image'),
            'aboutImage' => WebsiteSetting::get('home_description_image'),
            'whatsapp' => preg_replace('/\D+/', '', (string) WebsiteSetting::get('whatsapp_number', '')),
            'featuredProducts' => Product::public()->with('category:id,name')->orderBy('name')->limit(6)->get(),
            'galleryAlbums' => GalleryAlbum::active()->ordered()
                ->with('activeImages:id,gallery_album_id,image_path,display_order')
                ->limit(4)->get(),
        ])->layoutData([
            'title' => $page?->meta_title ?: null,
            'description' => $page?->meta_description ?: WebsiteSetting::get('site_description', $tagline),
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => $siteName,
                'url' => url('/'),
                'telephone' => WebsiteSetting::get('phone'),
                'email' => WebsiteSetting::get('email'),
            ],
        ]);
    }
}

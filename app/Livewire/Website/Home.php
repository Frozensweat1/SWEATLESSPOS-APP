<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use App\Models\Product;
use App\Models\GalleryAlbum;
use Livewire\Attributes\Layout;

#[Layout('layouts.website')]
class Home extends Component
{
    public $page;
    public $settings = [];
    public $featuredProducts = [];
    public $galleryAlbums = [];
    public $aboutPage;
    public $heroBackgroundUrl = '';
    public $aboutBackgroundUrl = '';
    public $productsBackgroundUrl = '';
    public $galleryBackgroundUrl = '';

    public function mount()
    {
        try {
            $this->page = WebsitePage::findBySlugOrFail('home');
        } catch (\Exception $e) {
            $this->page = new WebsitePage([
                'title' => 'Welcome to Our Store',
                'slug' => 'home',
                'content' => '<h1>Welcome!</h1><p>This is the home page.</p>',
            ]);
        }

        // Load website settings
        $this->settings = [
            'site_name' => WebsiteSetting::get('site_name', 'Sweatless POS'),
            'site_tagline' => WebsiteSetting::get('site_tagline', 'Your trusted business partner'),
            'hero_background' => WebsiteSetting::get('hero_background_image'),
            'about_background' => WebsiteSetting::get('about_background_image'),
            'products_background' => WebsiteSetting::get('products_background_image'),
            'gallery_background' => WebsiteSetting::get('gallery_background_image'),
            'logo' => WebsiteSetting::get('logo_image'),
            'home_description_image' => WebsiteSetting::get('home_description_image'),
        ];

        // Build background URLs
        $this->heroBackgroundUrl = $this->settings['hero_background'] ? 'url(' . asset('storage/' . $this->settings['hero_background']) . ')' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        $this->aboutBackgroundUrl = $this->settings['about_background'] ? 'url(' . asset('storage/' . $this->settings['about_background']) . ')' : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        $this->productsBackgroundUrl = $this->settings['products_background'] ? 'url(' . asset('storage/' . $this->settings['products_background']) . ')' : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
        $this->galleryBackgroundUrl = $this->settings['gallery_background'] ? 'url(' . asset('storage/' . $this->settings['gallery_background']) . ')' : 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';

        // Load featured products (limit to 6)
        $this->featuredProducts = Product::where('is_active', true)
            ->orderBy('name')
            ->limit(6)
            ->get();

        // Load gallery albums
        $this->galleryAlbums = GalleryAlbum::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Load about page
        try {
            $this->aboutPage = WebsitePage::findBySlugOrFail('about');
        } catch (\Exception $e) {
            $this->aboutPage = null;
        }
    }

    public function render()
    {
        return view('livewire.website.home');
    }
}

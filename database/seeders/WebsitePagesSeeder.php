<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsitePage;
use App\Models\User;

class WebsitePagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or super-admin
        $user = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['super-admin', 'admin']);
        })->first() ?? User::first();

        $userId = $user?->id ?? 1;

        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'content' => '<h1>Welcome to Our Store</h1><p>This is the home page of our online store. Browse our products and services.</p>',
                'meta_title' => 'Home | SweatlessPOS',
                'meta_description' => 'Welcome to our online store',
                'is_active' => true,
            ],
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h1>About Us</h1><p>Learn more about our company and our mission.</p>',
                'meta_title' => 'About Us | SweatlessPOS',
                'meta_description' => 'Learn about our company',
                'is_active' => true,
            ],
            [
                'title' => 'Products',
                'slug' => 'products',
                'content' => '<h1>Our Products</h1><p>Browse our collection of quality products.</p>',
                'meta_title' => 'Products | SweatlessPOS',
                'meta_description' => 'Browse our products',
                'is_active' => true,
            ],
            [
                'title' => 'Gallery',
                'slug' => 'gallery',
                'content' => '<h1>Gallery</h1><p>View our photo gallery.</p>',
                'meta_title' => 'Gallery | SweatlessPOS',
                'meta_description' => 'View our gallery',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Contact Us</h1><p>Get in touch with us for inquiries or support.</p>',
                'meta_title' => 'Contact Us | SweatlessPOS',
                'meta_description' => 'Contact us for more information',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            // Check if page already exists
            if (!WebsitePage::where('slug', $page['slug'])->exists()) {
                WebsitePage::create([
                    ...$page,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);
            }
        }
    }
}

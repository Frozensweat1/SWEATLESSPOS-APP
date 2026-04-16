<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteSetting;

class WebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'SweatlessPOS',
                'type' => 'string',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Your trusted POS solution',
                'type' => 'string',
            ],
            [
                'key' => 'site_description',
                'value' => 'Your trusted point of sale solution for modern business management',
                'type' => 'string',
            ],
            [
                'key' => 'email',
                'value' => 'contact@sweatlesspos.com',
                'type' => 'string',
            ],
            [
                'key' => 'phone',
                'value' => '+1 (555) 000-0000',
                'type' => 'string',
            ],
            [
                'key' => 'address',
                'value' => '123 Main Street, Your City, Country',
                'type' => 'string',
            ],
            [
                'key' => 'map_url',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'map_latitude',
                'value' => '40.7128',
                'type' => 'string',
            ],
            [
                'key' => 'map_longitude',
                'value' => '-74.0060',
                'type' => 'string',
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com',
                'type' => 'string',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com',
                'type' => 'string',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com',
                'type' => 'string',
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com',
                'type' => 'string',
            ],
            [
                'key' => 'tiktok_url',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'hero_background_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'about_background_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'products_background_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'gallery_background_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'contact_background_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'logo_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'home_description_image',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'founder_name',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'founder_title',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'founder_bio',
                'value' => '',
                'type' => 'string',
            ],
            [
                'key' => 'founder_image',
                'value' => '',
                'type' => 'string',
            ],
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            if (!WebsiteSetting::where('key', $setting['key'])->exists()) {
                WebsiteSetting::create($setting);
            }
        }
    }
}

<?php

namespace App\Helpers;

class MapHelper
{
    /**
     * Generate a Google Maps embed URL from coordinates
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $zoom
     * @return string
     */
    public static function generateEmbedUrl($latitude, $longitude, $zoom = 17)
    {
        if (!$latitude || !$longitude) {
            return '';
        }

        // Generate Google Maps embed URL with coordinates
        $pb = "!1m18!1m12!1m3!1d3024.2219901290355!2d{$longitude}!3d{$latitude}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zStore!5e0!3m2!1sen!2s!4v1630000000000";

        return "https://www.google.com/maps/embed?pb={$pb}";
    }

    /**
     * Generate a Google Maps search URL for address
     *
     * @param string $address
     * @return string
     */
    public static function generateSearchUrl($address)
    {
        if (!$address) {
            return '';
        }

        $encodedAddress = urlencode($address);
        return "https://www.google.com/maps/search/{$encodedAddress}";
    }

    /**
     * Generate a Google Maps directions URL
     *
     * @param string $destination (address or coordinates)
     * @return string
     */
    public static function generateDirectionsUrl($destination)
    {
        if (!$destination) {
            return '';
        }

        $encoded = urlencode($destination);
        return "https://www.google.com/maps/dir/?api=1&destination={$encoded}";
    }

    /**
     * Get store location from WebsiteSettings
     *
     * @return array
     */
    public static function getStoreLocation()
    {
        return [
            'address' => \App\Models\WebsiteSetting::get('address', ''),
            'latitude' => \App\Models\WebsiteSetting::get('map_latitude', ''),
            'longitude' => \App\Models\WebsiteSetting::get('map_longitude', ''),
            'phone' => \App\Models\WebsiteSetting::get('phone', ''),
            'email' => \App\Models\WebsiteSetting::get('email', ''),
        ];
    }

    /**
     * Get Google Maps embed URL from store settings
     * Falls back to generated URL if custom URL not available
     *
     * @return string
     */
    public static function getStoreMapUrl()
    {
        // First check if a custom map URL is set
        $customMapUrl = \App\Models\WebsiteSetting::get('map_url', '');
        if ($customMapUrl) {
            return $customMapUrl;
        }

        // Fall back to generating URL from coordinates or address
        $location = static::getStoreLocation();

        if ($location['latitude'] && $location['longitude']) {
            return static::generateEmbedUrl($location['latitude'], $location['longitude']);
        }

        if ($location['address']) {
            return static::generateSearchUrl($location['address']);
        }

        return '';
    }
}

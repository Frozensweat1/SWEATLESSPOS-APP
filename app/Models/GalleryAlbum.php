<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'is_active',
        'display_order',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the images in this album
     */
    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    /**
     * Get active images ordered by display_order
     */
    public function activeImages()
    {
        return $this->images()->orderBy('display_order');
    }

    /**
     * Get featured image
     */
    public function featuredImage()
    {
        return $this->images()->where('is_featured', true)->first();
    }

    /**
     * Get the user who created this album
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to only active albums
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by display order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Scope to find by slug
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }
}

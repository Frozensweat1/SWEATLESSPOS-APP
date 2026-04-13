<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GalleryImage extends Model
{
    protected $fillable = [
        'gallery_album_id',
        'image_path',
        'title',
        'description',
        'display_order',
        'is_featured',
        'uploaded_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Get the album this image belongs to
     */
    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class);
    }

    /**
     * Get the user who uploaded this image
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope to featured images
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope ordered by display order
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Get the full image URL
     */
    public function getImageUrl()
    {
        return asset('storage/' . $this->image_path);
    }
}

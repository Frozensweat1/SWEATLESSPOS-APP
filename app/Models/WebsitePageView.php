<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_slug',
        'page_title',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'city',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeForPage($query, $slug)
    {
        return $query->where('page_slug', $slug);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    }
}

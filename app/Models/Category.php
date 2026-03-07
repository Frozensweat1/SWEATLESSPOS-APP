<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category_photo_path',
        'store_id'
    ];

    // A Category belongs to a Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function getImageAttribute($image)
    {

        if ($image && file_exists('storage/' . $image))
            return $image;
        else
            return 'categories/noimage.png';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'barcode',
        'sku',
        'description',
        'price',
        'cost_price',
        'stock_quantity',
        'category_id',
        'supplier_id',
        'manufacture_date',
        'expiry_date',
        'image'
    ];

    // Example method to auto-generate SKU if not provided
    public static function generateSKU($productName)
    {
        return strtoupper(substr($productName, 0, 3)) . '-' . time();
    }

    // Example method to validate SKU format
    public static function validateSKU($sku)
    {
        return preg_match('/^[A-Z0-9\-]+$/', $sku);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    protected $casts = [
        'expiry_date' => 'date',
        'manufacture_date' => 'date',
    ];

    public function getImageAttribute($image)
    {

        if (file_exists('storage/' . $image))
            return $image;
        else
            return 'categories/noimage.png';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
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
        'image',
        'store_id',
        'is_active',
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
    // A Product belongs to a Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Query Scopes
    public const WEBSITE_STORE_ID = 1;

    public function scopePublic($query)
    {
        return $query->where('store_id', self::WEBSITE_STORE_ID)->where('is_active', true);
    }

    public function getStockStatusAttribute(): string
    {
        $qty = (int) $this->stock_quantity;

        return $qty <= 0 ? 'out' : ($qty <= 10 ? 'low' : 'in');
    }

    public function scopeWhereStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeActive($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('stock_quantity', '<=', $threshold);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    public function scopeByBarcode($query, $barcode)
    {
        return $query->where('barcode', $barcode);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
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

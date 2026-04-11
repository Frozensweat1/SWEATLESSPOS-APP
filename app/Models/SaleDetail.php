<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'quantity',
        'price_per_unit',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'is_refunded'
    ];

    protected $casts = [

        'is_refunded' => 'boolean'
    ];

    // A SaleDetail belongs to a Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // A SaleDetail belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

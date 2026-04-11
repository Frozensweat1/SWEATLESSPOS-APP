<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'sale_detail_id',
        'product_id',
        'quantity',
        'refund_amount',
        'reason',
        'user_id',
        'store_id',
    ];

    // A Return belongs to a Sale
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // A Return belongs to a SaleDetail
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class);
    }

    // A Return belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // A Return belongs to a User (the employee who processed the return)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Query Scopes
    public function scopeWhereStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBySale($query, $saleId)
    {
        return $query->where('sale_id', $saleId);
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', 'like', "%{$reason}%");
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
    }
}

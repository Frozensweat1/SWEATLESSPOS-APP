<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDrawerSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'opening_balance',
        'closing_balance',
        'expected_cash',
        'cash_sales',
        'cash_refunds',
        'cash_expenses',
        'discrepancy',
        'opening_note',
        'closing_note',
        'status',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Query Scopes
    public function scopeWhereStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('opened_at', '>=', now()->subDays($days));
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereDate('opened_at', '>=', $startDate)
            ->whereDate('opened_at', '<=', $endDate);
    }

    public function scopeWithDiscrepancy($query)
    {
        return $query->where('discrepancy', '!=', 0);
    }
}

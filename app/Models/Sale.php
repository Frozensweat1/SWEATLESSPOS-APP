<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'total_amount',
        'discount_type',
        'discount_value',
        'discount_amount',
        'total_discount',
        'payment_method',
        'payment_status',
        'amount_paid',
        'amount_due',
        'due_date',
        'user_id',
        'store_id'
    ];

    // A Sale belongs to a Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // A Sale belongs to a Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // A Sale belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A Sale has many SaleDetails
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
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

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopeCash($query)
    {
        return $query->where('payment_method', 'cash');
    }

    public function scopeCredit($query)
    {
        return $query->where('payment_method', 'credit');
    }

    public function scopeWithCustomer($query)
    {
        return $query->with(['customer', 'saleDetails.product']);
    }

    public function scopeByPaymentMethod($query, $paymentMethod)
    {
        return $query->where('payment_method', $paymentMethod);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
    }
}

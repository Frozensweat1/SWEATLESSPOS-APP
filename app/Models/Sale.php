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
}

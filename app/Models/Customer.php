<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'loyalty_points',
        'image',
        'status',
        'store_id'
    ];

    protected $casts = [
        'loyalty_points' => 'integer',
    ];

    // A Customer belongs to a Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Define a relationship to the Sale model
    public function sales()
    {
        // Assuming a 'customer_id' foreign key exists in the 'sales' table
        return $this->hasMany(Sale::class);
    }

    // Optionally, you can define an accessor for `total_amount_sum` to provide a default value
    public function getTotalAmountSumAttribute()
    {
        // Returns a default value if no sales are associated or sum hasn't been calculated
        return $this->sales()->sum('total_amount');
    }

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Query Scopes
    public function scopeWhereStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeWithDebt($query)
    {
        return $query->whereHas('sales', function ($sale) {
            $sale->where('payment_status', '!=', 'paid');
        });
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'like', "%{$searchTerm}%")
            ->orWhere('email', 'like', "%{$searchTerm}%")
            ->orWhere('phone', 'like', "%{$searchTerm}%");
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }
}

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

    // Mutator for phone number formatting
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    // Scope for active customers
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

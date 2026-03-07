<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'notes',
        'store_id'
    ];

    // A Supplier belongs to a Store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'id');
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
}

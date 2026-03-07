<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo_path',
        'initial_balance',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'initial_balance' => 'decimal:2',
    ];

    // A Store has many Users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // A Store has many Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // A Store has many Categories
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // A Store has many Customers
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    // A Store has many Suppliers
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    // A Store has many Sales
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

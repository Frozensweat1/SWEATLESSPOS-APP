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
        'user_id'
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
}

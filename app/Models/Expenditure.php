<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'amount',
        'category',
        'expenditure_date',
        'store_id',
        'user_id',
        'supplier_id',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expenditure_date' => 'date',
    ];

    /**
     * Get the user who recorded the expenditure.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the supplier associated with the expenditure.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

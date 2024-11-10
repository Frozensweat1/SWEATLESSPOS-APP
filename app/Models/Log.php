<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'event_type',
        'description',
        'user_id',
        'ip_address',
        'old_data',
        'new_data'
    ];
}

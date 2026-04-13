<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteContactFormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_response',
        'responded_at',
        'responded_by',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function respond($response, $userId)
    {
        $this->update([
            'admin_response' => $response,
            'responded_by' => $userId,
            'responded_at' => now(),
            'status' => 'responded',
        ]);
    }
}

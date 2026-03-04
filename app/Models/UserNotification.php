<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'action_url', 'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function getIconClass(): string
    {
        return match ($this->type) {
            'listing_approved' => 'text-green-600',
            'listing_rejected' => 'text-red-500',
            'new_inquiry' => 'text-blue-600',
            'new_review' => 'text-amber-500',
            'support_reply' => 'text-purple-600',
            default => 'text-gray-500',
        };
    }

    public function getIcon(): string
    {
        return match ($this->type) {
            'listing_approved' => 'check_circle',
            'listing_rejected' => 'cancel',
            'new_inquiry' => 'mail',
            'new_review' => 'star',
            'support_reply' => 'support_agent',
            default => 'notifications',
        };
    }
}

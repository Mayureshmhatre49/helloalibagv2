<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'listing_id', 'user_id', 'name', 'email', 'phone',
        'message', 'check_in', 'check_out', 'guests',
        'status', 'owner_reply', 'replied_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'replied_at' => 'datetime',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['new']);
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'new' => 'bg-blue-50 text-blue-700 border-blue-200',
            'read' => 'bg-amber-50 text-amber-700 border-amber-200',
            'replied' => 'bg-green-50 text-green-700 border-green-200',
            default => 'bg-gray-50 text-gray-600 border-gray-200',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'new' => 'New',
            'read' => 'Read',
            'replied' => 'Replied',
            default => ucfirst($this->status),
        };
    }
}

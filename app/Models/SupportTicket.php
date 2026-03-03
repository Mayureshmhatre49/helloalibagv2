<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id', 'listing_id', 'subject', 'category',
        'priority', 'status', 'assigned_to', 'resolved_at', 'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at', 'asc');
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    // Helpers
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'open' => 'bg-blue-50 text-blue-700 border-blue-200',
            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
            'resolved' => 'bg-green-50 text-green-700 border-green-200',
            'closed' => 'bg-gray-50 text-gray-600 border-gray-200',
            default => 'bg-gray-50 text-gray-600 border-gray-200',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => ucfirst($this->status),
        };
    }

    public function getPriorityBadgeClass(): string
    {
        return match ($this->priority) {
            'low' => 'bg-gray-50 text-gray-600 border-gray-200',
            'normal' => 'bg-blue-50 text-blue-700 border-blue-200',
            'high' => 'bg-orange-50 text-orange-700 border-orange-200',
            'urgent' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-gray-50 text-gray-600 border-gray-200',
        };
    }

    public function getTimeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }
}

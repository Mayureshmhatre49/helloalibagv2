<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id', 'user_id', 'check_in', 'check_out',
        'guests', 'message', 'status', 'total_price', 'owner_notes', 'confirmed_at',
    ];

    protected $casts = [
        'check_in'     => 'date',
        'check_out'    => 'date',
        'confirmed_at' => 'datetime',
        'total_price'  => 'decimal:2',
    ];

    // Relationships
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)    { return $query->where('status', 'pending'); }
    public function scopeConfirmed($query)  { return $query->where('status', 'confirmed'); }
    public function scopeDeclined($query)   { return $query->where('status', 'declined'); }
    public function scopeCompleted($query)  { return $query->where('status', 'completed'); }

    // Helpers
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isConfirmed(): bool { return $this->status === 'confirmed'; }
    public function isDeclined(): bool  { return $this->status === 'declined'; }

    public function getNights(): int
    {
        if (!$this->check_in || !$this->check_out) return 0;
        return $this->check_in->diffInDays($this->check_out);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'confirmed'  => 'bg-green-50 text-green-700 border-green-200',
            'declined'   => 'bg-red-50 text-red-700 border-red-200',
            'completed'  => 'bg-blue-50 text-blue-700 border-blue-200',
            'cancelled'  => 'bg-slate-100 text-slate-500 border-slate-200',
            default      => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    public function getStatusLabel(): string
    {
        return ucfirst($this->status);
    }
}

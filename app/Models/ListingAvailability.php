<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingAvailability extends Model
{
    protected $fillable = [
        'listing_id', 'date', 'status', 'price_override', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'price_override' => 'decimal:2',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}

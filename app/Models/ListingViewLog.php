<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingViewLog extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id', 'viewed_on', 'views'];

    protected $casts = [
        'viewed_on' => 'date',
        'views' => 'integer',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}

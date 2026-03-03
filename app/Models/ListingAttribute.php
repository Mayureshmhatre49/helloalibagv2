<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id', 'attribute_key', 'attribute_value'];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'category', 'sort_order'];

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class, 'listing_amenity');
    }
}

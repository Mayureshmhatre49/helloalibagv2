<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingImage extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id', 'path', 'thumbnail', 'alt_text', 'sort_order', 'is_primary', 'image_type'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }

        // Normalise any leading "/storage/" (legacy ImageService paths) so the
        // URL never doubles up to "/storage//storage/…".
        $clean = ltrim(preg_replace('#^/?storage/#', '', $this->path), '/');

        return asset('storage/' . $clean);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if (empty($this->thumbnail)) {
            return $this->url;
        }

        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }

        $clean = ltrim(preg_replace('#^/?storage/#', '', $this->thumbnail), '/');

        return asset('storage/' . $clean);
    }
}

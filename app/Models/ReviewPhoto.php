<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'path', 'thumbnail', 'sort_order'];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    /**
     * Public URL for the full-size photo. Handles both stored paths and
     * full external URLs (for forward-compat with CDN imports).
     */
    public function url(): string
    {
        if (\str_starts_with($this->path, 'http://') || \str_starts_with($this->path, 'https://')) {
            return $this->path;
        }

        return asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $this->path), '/'));
    }

    public function thumbnailUrl(): string
    {
        $t = $this->thumbnail ?: $this->path;

        if (\str_starts_with($t, 'http://') || \str_starts_with($t, 'https://')) {
            return $t;
        }

        return asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $t), '/'));
    }
}

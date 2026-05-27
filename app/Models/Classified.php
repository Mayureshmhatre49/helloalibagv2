<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Classified extends Model implements Auditable
{
    use HasFactory, HasSlug, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'is_negotiable', 'condition',
        'classified_category_id', 'area_id', 'seller_id',
        'status', 'rejection_reason', 'approved_by', 'approved_at',
        'contact_phone', 'contact_whatsapp', 'views_count',
        'is_featured', 'featured_until', 'sold_at', 'expires_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_negotiable' => 'boolean',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'featured_until' => 'datetime',
        'sold_at' => 'datetime',
        'expires_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public const CONDITIONS = [
        'new'      => 'Brand New',
        'like_new' => 'Like New',
        'good'     => 'Good',
        'fair'     => 'Fair',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(ClassifiedCategory::class, 'classified_category_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ClassifiedImage::class)->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->whereFullText(['title', 'description'], $term);
    }

    // Helpers
    public function getPrimaryImageUrl(): ?string
    {
        $image = $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();

        if (!$image) return null;

        return str_starts_with($image->path, 'http')
            ? $image->path
            : asset('storage/' . $image->path);
    }

    public function getConditionLabel(): ?string
    {
        return $this->condition ? (self::CONDITIONS[$this->condition] ?? null) : null;
    }

    public function isSold(): bool
    {
        return $this->status === 'sold';
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}

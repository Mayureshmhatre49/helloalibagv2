<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Guide extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title', 'slug', 'hero_image', 'hero_image_alt',
        'intro', 'content', 'focus_keyword',
        'meta_title', 'meta_description',
        'reading_time', 'views_count',
        'is_featured', 'is_published', 'published_at',
        'author_id',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class, 'guide_listing')
            ->withPivot(['position', 'blurb'])
            ->withTimestamps()
            ->orderBy('guide_listing.position');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function heroImageUrl(): ?string
    {
        if (empty($this->hero_image)) {
            return null;
        }

        if (\str_starts_with($this->hero_image, 'http://') || \str_starts_with($this->hero_image, 'https://')) {
            return $this->hero_image;
        }

        return asset('storage/' . ltrim($this->hero_image, '/'));
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}

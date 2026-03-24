<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Listing extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title', 'slug', 'category_id', 'area_id', 'description', 'price',
        'status', 'rejection_reason', 'is_featured', 'is_premium', 'created_by', 'approved_by',
        'approved_at', 'views_count', 'address', 'phone', 'email',
        'website', 'whatsapp', 'city_id', 'subscription_ready',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
        'subscription_ready' => 'boolean',
        'approved_at' => 'datetime',
        'views_count' => 'integer',
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
        return $this->belongsTo(Category::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function listingAttributes(): HasMany
    {
        return $this->hasMany(ListingAttribute::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ListingAttributeValue::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ListingImage::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'listing_amenity');
    }

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_listing_relations', 'listing_id', 'blog_post_id');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) return $query;

        return $query->whereRaw(
            'MATCH(title, description) AGAINST(? IN BOOLEAN MODE)',
            [$term . '*']
        );
    }

    // Helpers
    public function getDynamicAttribute(string $key): ?string
    {
        $attr = $this->listingAttributes()->where('attribute_key', $key)->first();
        return $attr?->attribute_value;
    }

    public function getAverageRating(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getPrimaryImageUrl(): ?string
    {
        $image = $this->images()->where('is_primary', true)->first()
            ?? $this->images()->first();

        if (!$image) return null;
        
        return str_starts_with($image->path, 'http') 
            ? $image->path 
            : asset('storage/' . $image->path);
    }

    public function setDynamicAttribute(string $key, ?string $value): void
    {
        $this->listingAttributes()->updateOrCreate(
            ['attribute_key' => $key],
            ['attribute_value' => $value]
        );
    }

    public function getDynamicAttributes(): array
    {
        return $this->listingAttributes()
            ->pluck('attribute_value', 'attribute_key')
            ->toArray();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Tags relationship for 'Best For' Smart Tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'listing_tag');
    }

    /**
     * Listing Quality Score (0–100)
     * image(+20), description(+20), phone(+15), amenities(+15), area(+10), price(+10), whatsapp(+10)
     */
    public function getQualityScore(): int
    {
        $score = 0;

        if ($this->images()->count() > 0) $score += 20;
        if (!empty($this->description) && strlen($this->description) > 50) $score += 20;
        if (!empty($this->phone)) $score += 15;
        if ($this->amenities()->count() > 0) $score += 15;
        if (!empty($this->area_id)) $score += 10;
        if (!empty($this->price) && $this->price > 0) $score += 10;
        if (!empty($this->whatsapp)) $score += 10;

        return $score;
    }

    public function getQualityLabel(): string
    {
        $score = $this->getQualityScore();
        if ($score >= 80) return 'Excellent';
        if ($score >= 60) return 'Good';
        if ($score >= 40) return 'Fair';
        return 'Needs Work';
    }

    public function getQualityColor(): string
    {
        $score = $this->getQualityScore();
        if ($score >= 80) return 'text-emerald-600 bg-emerald-50';
        if ($score >= 60) return 'text-blue-600 bg-blue-50';
        if ($score >= 40) return 'text-amber-600 bg-amber-50';
        return 'text-red-600 bg-red-50';
    }
}

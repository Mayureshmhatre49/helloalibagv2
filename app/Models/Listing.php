<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Listing extends Model implements Auditable
{
    use HasFactory, HasSlug, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title', 'slug', 'category_id', 'area_id', 'description', 'price',
        'status', 'rejection_reason', 'is_featured', 'is_premium', 'created_by', 'approved_by',
        'approved_at', 'views_count', 'address', 'latitude', 'longitude', 'phone', 'email',
        'website', 'whatsapp', 'google_business_url', 'city_id', 'subscription_ready',
        'event_start_at', 'event_end_at', 'event_is_recurring',
        'is_verified', 'verified_at', 'verification_note', 'verified_by',
        'payment_received_at', 'payment_recorded_by', 'payment_note',
        'rejected_at', 'rejected_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
        'subscription_ready' => 'boolean',
        'approved_at' => 'datetime',
        'views_count' => 'integer',
        'event_start_at' => 'datetime',
        'event_end_at' => 'datetime',
        'event_is_recurring' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'payment_received_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    // ── Event-specific scopes (for the /events-calendar view) ──

    public function scopeUpcomingEvents($query)
    {
        return $query->whereNotNull('event_start_at')
            ->where(function ($q) {
                $q->where('event_start_at', '>=', now())
                  ->orWhere('event_end_at', '>=', now());
            })
            ->orderBy('event_start_at');
    }

    public function scopeEventsBetween($query, \DateTimeInterface $from, \DateTimeInterface $to)
    {
        return $query->whereNotNull('event_start_at')
            ->where('event_start_at', '<=', $to)
            ->where(function ($q) use ($from) {
                $q->where('event_end_at', '>=', $from)
                  ->orWhere(function ($q2) use ($from) {
                      $q2->whereNull('event_end_at')->where('event_start_at', '>=', $from);
                  });
            })
            ->orderBy('event_start_at');
    }

    public function scopeThisWeekend($query)
    {
        // "This weekend" means Sat 00:00 IST through Sun 23:59 IST of the
        // current calendar week — works whether today is Mon or Sun.
        $tz = 'Asia/Kolkata';
        $sat = now($tz)->next(\Carbon\CarbonInterface::SATURDAY)->startOfDay();
        // If today *is* the weekend, use the current Sat/Sun window.
        if (now($tz)->isWeekend()) {
            $sat = now($tz)->startOfWeek(\Carbon\CarbonInterface::SATURDAY)->startOfDay();
        }
        $sun = $sat->copy()->addDay()->endOfDay();

        return $query->eventsBetween($sat, $sun);
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

        if (str_starts_with($image->path, 'http')) {
            return $image->path;
        }

        // Normalise any leading "/storage/" (legacy ImageService paths) so the
        // URL never doubles up to "/storage//storage/…".
        $clean = ltrim(preg_replace('#^/?storage/#', '', $image->path), '/');

        return asset('storage/' . $clean);
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

        // Also record the hit against today's bucket so the owner dashboard can
        // show a real trend rather than just a lifetime total. Done as a single
        // atomic upsert — firstOrCreate + increment would race under concurrent
        // views and trip the unique index. Never let analytics break a page view.
        try {
            \Illuminate\Support\Facades\DB::statement(
                'INSERT INTO listing_view_logs (listing_id, viewed_on, views, created_at, updated_at)
                 VALUES (?, ?, 1, NOW(), NOW())
                 ON DUPLICATE KEY UPDATE views = views + 1, updated_at = NOW()',
                [$this->id, now()->toDateString()]
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Listing view log failed: ' . $e->getMessage());
        }
    }

    public function viewLogs(): HasMany
    {
        return $this->hasMany(ListingViewLog::class);
    }

    /**
     * Real Estate is a paid category settled offline, so a listing in it may
     * not go live until an admin has recorded that payment was collected.
     */
    public function requiresOfflinePayment(): bool
    {
        return $this->category?->slug === 'real-estate';
    }

    public function offlinePaymentReceived(): bool
    {
        return $this->payment_received_at !== null;
    }

    /** Blocks approval: needs payment collected, but none recorded yet. */
    public function awaitingOfflinePayment(): bool
    {
        return $this->requiresOfflinePayment() && ! $this->offlinePaymentReceived();
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

    /**
     * SEO Score (0–100)
     * meta_title(+30), meta_description(+30), og_image(+20), canonical_url(+10), meta_keywords(+10)
     */
    public function getSeoScore(): int
    {
        $seo = $this->seoMeta;
        if (!$seo) return 0;

        $score = 0;
        if (!empty($seo->meta_title)) $score += 30;
        if (!empty($seo->meta_description)) $score += 30;
        if (!empty($seo->og_image)) $score += 20;
        if (!empty($seo->canonical_url)) $score += 10;
        if (!empty($seo->meta_keywords)) $score += 10;

        return $score;
    }

    public function getSeoLabel(): string
    {
        $score = $this->getSeoScore();
        if ($score >= 80) return 'Excellent';
        if ($score >= 60) return 'Good';
        if ($score >= 40) return 'Fair';
        if ($score > 0) return 'Needs Work';
        return 'Missing';
    }

    public function getSeoColor(): string
    {
        $score = $this->getSeoScore();
        if ($score >= 80) return 'text-emerald-600 bg-emerald-50';
        if ($score >= 60) return 'text-blue-600 bg-blue-50';
        if ($score >= 40) return 'text-amber-600 bg-amber-50';
        return 'text-red-600 bg-red-50';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Trip extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id', 'name', 'slug', 'share_token',
        'start_date', 'end_date', 'party_size', 'notes', 'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_public' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Ensure every trip has a share_token even if not explicitly set.
        static::creating(function (Trip $trip) {
            if (empty($trip->share_token)) {
                $trip->share_token = Str::random(24);
            }
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->extraScope(fn (Builder $q) => $q->where('user_id', $this->user_id));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listings(): BelongsToMany
    {
        return $this->belongsToMany(Listing::class, 'trip_listing')
            ->withPivot(['position', 'note'])
            ->withTimestamps()
            ->orderBy('trip_listing.position');
    }

    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function getDurationLabelAttribute(): ?string
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return $days . ' day' . ($days === 1 ? '' : 's');
    }

    public function getDateRangeLabelAttribute(): ?string
    {
        if (!$this->start_date) {
            return null;
        }
        if (!$this->end_date || $this->end_date->equalTo($this->start_date)) {
            return $this->start_date->format('M j, Y');
        }
        if ($this->start_date->isSameMonth($this->end_date)) {
            return $this->start_date->format('M j') . '–' . $this->end_date->format('j, Y');
        }
        return $this->start_date->format('M j') . ' – ' . $this->end_date->format('M j, Y');
    }
}

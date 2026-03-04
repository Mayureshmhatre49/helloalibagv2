<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'model');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class);
    }

    public function approvedListings(): HasMany
    {
        return $this->hasMany(Listing::class)->where('status', 'approved');
    }
}

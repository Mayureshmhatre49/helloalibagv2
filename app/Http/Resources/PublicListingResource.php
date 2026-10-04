<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Explicit field whitelist for the public integration API. Never falls
 * back to the model's toArray() — anything not listed here is intentionally
 * excluded (internal moderation/ownership/payment fields, view counts, etc).
 */
class PublicListingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $images = $this->images
            ->sortByDesc('is_primary')
            ->values()
            ->map(fn ($image) => [
                'url' => $image->url,
                'is_primary' => (bool) $image->is_primary,
                'alt_text' => $image->alt_text,
            ]);

        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price !== null ? (float) $this->price : null,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'area' => $this->whenLoaded('area', fn () => $this->area ? [
                'id' => $this->area->id,
                'name' => $this->area->name,
                'slug' => $this->area->slug,
            ] : null),
            'images' => $images,
            'amenities' => $this->whenLoaded('amenities', fn () => $this->amenities->pluck('name')->values()),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->pluck('name')->values()),
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,
            'google_business_url' => $this->google_business_url,
            'is_featured' => (bool) $this->is_featured,
            'is_premium' => (bool) $this->is_premium,
            'is_verified' => (bool) $this->is_verified,
            'approved_at' => $this->approved_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'seo' => $this->whenLoaded('seoMeta', function () {
                if (! $this->seoMeta) {
                    return null;
                }

                return [
                    'meta_title' => $this->seoMeta->meta_title,
                    'meta_description' => $this->seoMeta->meta_description,
                    'og_image' => $this->seoMeta->og_image,
                ];
            }),
        ];
    }
}

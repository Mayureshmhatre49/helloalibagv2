<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Support\Facades\Cache;

class MapService
{
    private const int CACHE_TTL = 600; // 10 min — re-cached when listing data changes

    /**
     * Return marker data for all approved listings.
     * Each marker carries enough info for popup display without a second request.
     */
    public function getApprovedListingMarkers(): array
    {
        return Cache::remember('map.markers.approved', self::CACHE_TTL, function () {
            $listings = Listing::approved()
                ->with(['category:id,name,slug,icon', 'area:id,name,latitude,longitude', 'images' => fn ($q) => $q->orderBy('sort_order')->limit(1)])
                ->whereHas('area', fn ($q) => $q->whereNotNull('latitude')->whereNotNull('longitude'))
                ->get();

            return $listings->map(function (Listing $listing) {
                [$lat, $lng] = $this->resolveCoordinates($listing);

                return [
                    'id' => $listing->id,
                    'lat' => $lat,
                    'lng' => $lng,
                    'title' => $listing->title,
                    'category' => $listing->category?->name,
                    'category_slug' => $listing->category?->slug,
                    'area' => $listing->area?->name,
                    'price' => $listing->price ? '₹' . number_format((float) $listing->price, 0) : null,
                    'url' => route('listing.show', [$listing->category->slug, $listing->slug]),
                    'image' => $this->resolveImageUrl($listing->images->first()?->path),
                    'is_featured' => (bool) $listing->is_featured,
                ];
            })->values()->all();
        });
    }

    /**
     * Normalise an image `path` value to a public URL.
     * Some imports store full https URLs; others store a relative storage path.
     */
    private function resolveImageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (\str_starts_with($path, 'http://') || \str_starts_with($path, 'https://')) {
            return $path;
        }

        // Strip leading "/storage/" if already present, then prepend asset URL
        $clean = ltrim(preg_replace('#^/?storage/#', '', $path), '/');
        return asset('storage/' . $clean);
    }

    /**
     * Resolve marker coordinates:
     *  - If the listing has explicit lat/lng, use them.
     *  - Otherwise scatter the marker within ~600m of the area centroid
     *    using a deterministic offset derived from the listing id (so the
     *    pin stays in the same spot on every render).
     */
    private function resolveCoordinates(Listing $listing): array
    {
        if ($listing->latitude && $listing->longitude) {
            return [(float) $listing->latitude, (float) $listing->longitude];
        }

        $areaLat = (float) ($listing->area?->latitude ?? 18.6414);
        $areaLng = (float) ($listing->area?->longitude ?? 72.8722);

        // Deterministic offset of up to ~600m using listing id as seed
        // 0.005° ≈ 555 m at this latitude
        $jitter = 0.005;
        $hash = crc32((string) $listing->id);
        $latOffset = (($hash % 1000) / 1000 - 0.5) * 2 * $jitter;
        $lngOffset = ((($hash >> 10) % 1000) / 1000 - 0.5) * 2 * $jitter;

        return [round($areaLat + $latOffset, 6), round($areaLng + $lngOffset, 6)];
    }

    /**
     * Build per-category metadata for the map legend / filter chips.
     */
    public function getCategoryLegend(): array
    {
        return Cache::remember('map.legend.categories', self::CACHE_TTL, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'icon'])
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'icon' => $c->icon,
                    'color' => $this->colorForCategory($c->slug),
                ])
                ->values()
                ->all();
        });
    }

    /**
     * Tailwind-aligned colors mapped by category slug. Kept in PHP so the
     * blade view can apply the same hue to filter chips and pins.
     */
    public function colorForCategory(?string $slug): string
    {
        return match ($slug) {
            'stay' => '#1183d4',          // primary blue
            'eat' => '#e8831a',           // orange
            'explore' => '#10b981',       // emerald
            'events' => '#a855f7',        // violet
            'services' => '#0891b2',      // cyan
            'real-estate' => '#e8a020',   // gold
            default => '#64748b',         // slate (fallback)
        };
    }
}

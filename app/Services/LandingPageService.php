<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Resolves URL slugs like "stay-in-mandwa" or "budget-villas-in-alibaug"
 * into structured landing-page data (category, area, price filter, copy,
 * listings, FAQs, schema markup).
 *
 * Returns null when the slug doesn't match a real combination — the
 * controller turns that into a 404.
 */
class LandingPageService
{
    // Singular friendly name → category slug.
    // Lets us accept both "/stay-in-mandwa" and "/villas-in-mandwa".
    private const array CATEGORY_ALIASES = [
        'stay' => 'stay',
        'stays' => 'stay',
        'villa' => 'stay',
        'villas' => 'stay',
        'hotel' => 'stay',
        'hotels' => 'stay',
        'eat' => 'eat',
        'restaurant' => 'eat',
        'restaurants' => 'eat',
        'dining' => 'eat',
        'explore' => 'explore',
        'things-to-do' => 'explore',
        'activities' => 'explore',
        'events' => 'events',
        'event' => 'events',
        'services' => 'services',
        'service' => 'services',
        'real-estate' => 'real-estate',
        'property' => 'real-estate',
        'properties' => 'real-estate',
    ];

    private const array PRICE_BUCKETS = [
        'budget' => ['max' => 10000, 'label' => 'Budget', 'phrase' => 'under ₹10,000'],
        'mid-range' => ['min' => 10000, 'max' => 25000, 'label' => 'Mid-Range', 'phrase' => '₹10,000–₹25,000'],
        'luxury' => ['min' => 25000, 'label' => 'Luxury', 'phrase' => 'over ₹25,000'],
    ];

    /**
     * Parse a slug like "stay-in-mandwa" or "budget-villas-in-alibaug"
     * and return a context array, or null if it doesn't resolve.
     */
    public function resolve(string $slug): ?array
    {
        $slug = strtolower($slug);

        // Detect optional bucket prefix.
        $bucketKey = null;
        foreach (array_keys(self::PRICE_BUCKETS) as $b) {
            if (\str_starts_with($slug, "{$b}-")) {
                $bucketKey = $b;
                $slug = substr($slug, \strlen($b) + 1);
                break;
            }
        }

        // Slug must contain "-in-" with non-empty parts on each side.
        if (!str_contains($slug, '-in-')) {
            return null;
        }
        [$categoryPart, $areaPart] = explode('-in-', $slug, 2);
        if ($categoryPart === '' || $areaPart === '') {
            return null;
        }

        $categorySlug = self::CATEGORY_ALIASES[$categoryPart] ?? null;
        if (!$categorySlug) {
            return null;
        }

        $category = Category::where('slug', $categorySlug)->where('is_active', true)->first();
        if (!$category) {
            return null;
        }

        // "alibaug" is an aggregate "everywhere" landing — no area filter.
        $area = null;
        if ($areaPart !== 'alibaug') {
            $area = Area::where('slug', $areaPart)->where('is_active', true)->first();
            if (!$area) {
                return null;
            }
        }

        $bucket = $bucketKey ? self::PRICE_BUCKETS[$bucketKey] : null;

        return [
            'slug' => $bucketKey ? "{$bucketKey}-{$categoryPart}-in-{$areaPart}" : "{$categoryPart}-in-{$areaPart}",
            'category' => $category,
            'area' => $area,
            'bucket_key' => $bucketKey,
            'bucket' => $bucket,
            'noun' => $this->nounFor($categoryPart, $categorySlug),
            'noun_singular' => $this->nounFor($categoryPart, $categorySlug, singular: true),
        ];
    }

    /**
     * Build the listing query for a resolved context.
     * Returns a paginator (15 per page).
     */
    public function listings(array $ctx, int $perPage = 15)
    {
        $query = Listing::approved()
            ->where('category_id', $ctx['category']->id)
            ->with(['category:id,name,slug,icon', 'area:id,name,slug', 'images' => fn ($q) => $q->orderBy('sort_order')]);

        if ($ctx['area']) {
            $query->where('area_id', $ctx['area']->id);
        }

        if ($ctx['bucket']) {
            if (isset($ctx['bucket']['min'])) {
                $query->where('price', '>=', $ctx['bucket']['min']);
            }
            if (isset($ctx['bucket']['max'])) {
                $query->where('price', '<=', $ctx['bucket']['max']);
            }
            // Only listings with a price set (else the bucket label lies).
            $query->whereNotNull('price');
        }

        return $query->orderByDesc('is_featured')->latest()->paginate($perPage);
    }

    /**
     * Sibling/related landing-page links for the internal-linking footer.
     * Pulls 4–6 related slugs so the page funnels users sideways.
     */
    public function relatedLinks(array $ctx): array
    {
        return Cache::remember('landing.related.' . $ctx['slug'], 600, function () use ($ctx) {
            $links = [];

            // Same category, other areas.
            $otherAreas = Area::where('is_active', true)
                ->when($ctx['area'], fn ($q) => $q->where('id', '!=', $ctx['area']->id))
                ->whereHas('listings', fn ($q) => $q->approved()->where('category_id', $ctx['category']->id))
                ->orderBy('name')
                ->limit(4)
                ->get();

            foreach ($otherAreas as $a) {
                $links[] = [
                    'label' => "{$ctx['category']->name} in {$a->name}",
                    'url' => url('/' . $ctx['category']->slug . '-in-' . $a->slug),
                ];
            }

            // Other categories in the same area.
            if ($ctx['area']) {
                $otherCats = Category::where('is_active', true)
                    ->where('id', '!=', $ctx['category']->id)
                    ->whereHas('listings', fn ($q) => $q->approved()->where('area_id', $ctx['area']->id))
                    ->orderBy('sort_order')
                    ->limit(4)
                    ->get();

                foreach ($otherCats as $c) {
                    $links[] = [
                        'label' => "{$c->name} in {$ctx['area']->name}",
                        'url' => url('/' . $c->slug . '-in-' . $ctx['area']->slug),
                    ];
                }
            } else {
                // For Alibaug-wide pages, link to bucket variants if applicable.
                if (!$ctx['bucket'] && $ctx['category']->slug === 'stay') {
                    $links[] = ['label' => 'Budget villas in Alibaug', 'url' => url('/budget-villas-in-alibaug')];
                    $links[] = ['label' => 'Luxury villas in Alibaug', 'url' => url('/luxury-villas-in-alibaug')];
                }
            }

            return $links;
        });
    }

    /**
     * Auto-generated H1, intro paragraph, meta title and meta description.
     */
    public function copy(array $ctx, int $listingCount): array
    {
        $cat = $ctx['category'];
        $area = $ctx['area'];
        $bucket = $ctx['bucket'];
        $areaName = $area ? $area->name : 'Alibaug';
        $noun = $ctx['noun'];
        $nounSingular = $ctx['noun_singular'];

        $bucketPrefix = $bucket ? $bucket['label'] . ' ' : '';
        // Title-case the leading noun for the H1 ("Villas in Kihim" reads better than "villas in Kihim").
        $h1 = trim("{$bucketPrefix}" . ucfirst($noun) . " in {$areaName}");

        $countPhrase = $listingCount === 0
            ? 'curated picks coming soon'
            : ($listingCount === 1 ? '1 handpicked listing' : "{$listingCount} handpicked listings");

        $bucketPhrase = $bucket ? " priced {$bucket['phrase']}" : '';
        $areaPhrase = $area
            ? ($area->tagline ? " — {$area->tagline}" : '')
            : ' across all neighborhoods';

        $intro = "Browse {$countPhrase} of {$noun}{$bucketPhrase} in {$areaName}{$areaPhrase}. "
            . "Every listing is reviewed, verified and ready to inquire — no middlemen.";

        // Layout already prepends "Hello Alibaug —" so we don't repeat the brand here.
        $metaTitle = trim("{$bucketPrefix}" . ucfirst($noun) . " in {$areaName}");
        $metaDescription = $listingCount > 0
            ? "Discover {$countPhrase} of {$noun} in {$areaName}{$bucketPhrase}. Compare options, see real reviews and inquire directly with the host."
            : "Looking for {$noun} in {$areaName}? Curated picks are coming soon. Browse nearby areas or sign up for updates.";

        return compact('h1', 'intro', 'metaTitle', 'metaDescription');
    }

    /**
     * Auto-generated FAQs for the landing page. Context-aware.
     */
    public function faqs(array $ctx, LengthAwarePaginator $listings): array
    {
        $areaName = $ctx['area']?->name ?? 'Alibaug';
        $catName = $ctx['category']->name;
        $noun = $ctx['noun'];

        $faqs = [];

        $faqs[] = [
            'q' => "How many {$noun} are there in {$areaName}?",
            'a' => $listings->total() > 0
                ? "There are " . $listings->total() . " verified " . strtolower($noun) . " in {$areaName} on Hello Alibaug. New listings are added regularly — check back or sign up to be notified."
                : "We're actively curating {$noun} in {$areaName}. In the meantime, browse listings in nearby areas or send us a message about what you're looking for.",
        ];

        if ($ctx['bucket']) {
            $faqs[] = [
                'q' => "What's a good budget for " . strtolower($noun) . " in {$areaName}?",
                'a' => "This page filters to {$ctx['bucket']['phrase']}. Outside this range you'll find more options in our broader {$catName} category — quality varies, so always read recent reviews before booking.",
            ];
        }

        if ($ctx['area']) {
            $faqs[] = [
                'q' => "How do I reach {$areaName} from Mumbai?",
                'a' => "The fastest way is the ferry from Gateway of India to Mandwa, then a 20–40 minute drive to {$areaName} depending on location. Full route comparison is on our <a href=\"" . route('page.how-to-reach') . "\">How to Reach</a> page.",
            ];
            $faqs[] = [
                'q' => "What's the weather like in {$areaName}?",
                'a' => "{$areaName} shares Alibaug's coastal climate — pleasant November to February, hot April to May, heavy rain June to September. Check our <a href=\"" . route('weather.index') . "\">live weather forecast</a> before you travel.",
            ];
        }

        $faqs[] = [
            'q' => "Are the " . strtolower($noun) . " on Hello Alibaug verified?",
            'a' => "Yes — every listing is reviewed and approved by the Hello Alibaug team before going live. Pricing and amenities are kept up to date by the host directly.",
        ];

        $faqs[] = [
            'q' => "How do I book a " . strtolower($ctx['noun_singular']) . "?",
            'a' => "Click any listing to see full details and use the inquiry form. The host typically replies within 24 hours with availability and a final quote. No platform booking fee.",
        ];

        return $faqs;
    }

    /**
     * Friendly plural noun for the category (used in headlines + intro).
     * Allows us to say "villas in Mandwa" instead of "stay in Mandwa".
     */
    private function nounFor(string $urlPart, string $categorySlug, bool $singular = false): string
    {
        $byUrl = [
            'villas' => $singular ? 'villa' : 'villas',
            'villa' => $singular ? 'villa' : 'villas',
            'hotels' => $singular ? 'hotel' : 'hotels',
            'hotel' => $singular ? 'hotel' : 'hotels',
            'stays' => $singular ? 'stay' : 'stays',
            'restaurants' => $singular ? 'restaurant' : 'restaurants',
            'restaurant' => $singular ? 'restaurant' : 'restaurants',
            'dining' => $singular ? 'dining spot' : 'dining spots',
            'things-to-do' => $singular ? 'thing to do' : 'things to do',
            'activities' => $singular ? 'activity' : 'activities',
            'properties' => $singular ? 'property' : 'properties',
            'property' => $singular ? 'property' : 'properties',
        ];

        if (isset($byUrl[$urlPart])) {
            return $byUrl[$urlPart];
        }

        $byCategory = [
            'stay' => $singular ? 'place to stay' : 'places to stay',
            'eat' => $singular ? 'restaurant' : 'restaurants',
            'explore' => $singular ? 'experience' : 'experiences',
            'events' => $singular ? 'event' : 'events',
            'services' => $singular ? 'service' : 'services',
            'real-estate' => $singular ? 'property' : 'properties',
        ];

        return $byCategory[$categorySlug] ?? $urlPart;
    }
}

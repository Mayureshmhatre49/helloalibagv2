<?php

namespace App\Http\Controllers;

use App\Models\MapApiUsageLog;
use App\Services\MapApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceSearchController extends Controller
{
    // Same Alibaug taluka box the listing geocoder uses, so the picker can't
    // suggest a place the geocoder would later reject.
    private const LAT_MIN = 18.30;
    private const LAT_MAX = 18.90;
    private const LNG_MIN = 72.70;
    private const LNG_MAX = 73.10;

    private const GEOAPIFY_ENDPOINT = 'https://api.geoapify.com/v1/geocode/autocomplete';
    private const GOOGLE_ENDPOINT = 'https://places.googleapis.com/v1/places:searchText';

    public function __construct(protected MapApiService $mapApi) {}

    /**
     * Address autocomplete for the listing location picker.
     *
     * Proxied server-side rather than called from the browser so the API key
     * is never shipped to the client. Results are biased to Alibaug and
     * cached briefly, since typing an address fires several near-identical
     * requests per listing.
     *
     * Uses Google Places (Text Search, New) when the admin has enabled the
     * Google Maps integration, falling back to Geoapify otherwise — both for
     * a disabled integration and if a Google call itself fails, so the
     * search box never goes fully dark.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['results' => []]);
        }

        if ($this->mapApi->isEnabled()) {
            $results = $this->searchGoogle($query);
            if ($results !== null) {
                return response()->json(['results' => $results]);
            }
            // Google call failed — fall through to Geoapify below.
        }

        return response()->json(['results' => $this->searchGeoapify($query)]);
    }

    /**
     * Returns null (not an empty array) on failure, so the caller can tell
     * "Google returned zero matches" apart from "the Google call failed"
     * and fall back to Geoapify only for the latter.
     */
    private function searchGoogle(string $query): ?array
    {
        $cacheKey = 'places.google.' . md5(mb_strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($query) {
            try {
                $response = Http::timeout(8)
                    ->withHeaders([
                        'X-Goog-Api-Key' => $this->mapApi->apiKey(),
                        'X-Goog-FieldMask' => 'places.displayName,places.formattedAddress,places.location',
                    ])
                    ->post(self::GOOGLE_ENDPOINT, [
                        'textQuery' => $query,
                        'locationBias' => [
                            'rectangle' => [
                                'low' => ['latitude' => self::LAT_MIN, 'longitude' => self::LNG_MIN],
                                'high' => ['latitude' => self::LAT_MAX, 'longitude' => self::LNG_MAX],
                            ],
                        ],
                    ]);

                // Record the hit here, inside the cache closure, so a cached
                // response (no outbound call) never gets double-counted.
                $this->mapApi->recordHit(MapApiUsageLog::TYPE_LOCATION_SEARCH);

                if (! $response->successful()) {
                    Log::warning('Google Places search failed: HTTP ' . $response->status());
                    return null;
                }

                return collect($response->json('places') ?? [])
                    ->map(fn ($place) => [
                        'label' => $place['displayName']['text'] ?? 'Unknown',
                        'detail' => $place['formattedAddress'] ?? '',
                        'lat' => isset($place['location']['latitude']) ? round((float) $place['location']['latitude'], 7) : null,
                        'lon' => isset($place['location']['longitude']) ? round((float) $place['location']['longitude'], 7) : null,
                    ])
                    ->filter(fn ($r) => $r['lat'] !== null && $r['lon'] !== null)
                    ->take(6)
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('Google Places search error: ' . $e->getMessage());
                return null;
            }
        });
    }

    private function searchGeoapify(string $query): array
    {
        if (empty(config('services.geoapify.key'))) {
            return [];
        }

        $cacheKey = 'places.autocomplete.' . md5(mb_strtolower($query));

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($query) {
            try {
                $response = Http::timeout(8)->get(self::GEOAPIFY_ENDPOINT, [
                    'text' => $query,
                    'apiKey' => config('services.geoapify.key'),
                    'format' => 'json',
                    'limit' => 6,
                    'filter' => 'countrycode:in|rect:' . self::LNG_MIN . ',' . self::LAT_MIN . ',' . self::LNG_MAX . ',' . self::LAT_MAX,
                    'bias' => 'proximity:72.8722,18.6414',
                ]);

                if (! $response->successful()) {
                    Log::warning('Place autocomplete failed: HTTP ' . $response->status());
                    return [];
                }

                return collect($response->json('results') ?? [])
                    ->map(fn ($hit) => [
                        'label' => $hit['formatted'] ?? ($hit['address_line1'] ?? 'Unknown'),
                        'detail' => $hit['address_line2'] ?? '',
                        'lat' => isset($hit['lat']) ? round((float) $hit['lat'], 7) : null,
                        'lon' => isset($hit['lon']) ? round((float) $hit['lon'], 7) : null,
                    ])
                    ->filter(fn ($r) => $r['lat'] !== null && $r['lon'] !== null)
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('Place autocomplete error: ' . $e->getMessage());
                return [];
            }
        });
    }
}

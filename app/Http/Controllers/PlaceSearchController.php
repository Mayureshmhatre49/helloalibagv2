<?php

namespace App\Http\Controllers;

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

    private const ENDPOINT = 'https://api.geoapify.com/v1/geocode/autocomplete';

    /**
     * Address autocomplete for the listing location picker.
     *
     * Proxied server-side rather than called from the browser so the Geoapify
     * key is never shipped to the client. Results are biased to Alibaug and
     * cached briefly, since typing an address fires several near-identical
     * requests per listing.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 3) {
            return response()->json(['results' => []]);
        }

        if (empty(config('services.geoapify.key'))) {
            return response()->json(['results' => [], 'error' => 'search_unavailable']);
        }

        $cacheKey = 'places.autocomplete.' . md5(mb_strtolower($query));

        $results = Cache::remember($cacheKey, now()->addHours(6), function () use ($query) {
            try {
                $response = Http::timeout(8)->get(self::ENDPOINT, [
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

        return response()->json(['results' => $results]);
    }
}

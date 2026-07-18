<?php

namespace App\Console\Commands;

use App\Models\Listing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocodeListings extends Command
{
    protected $signature = 'listings:geocode
        {--dry-run : Show what would be geocoded without writing coordinates}
        {--all : Re-geocode every listing, not only those missing coordinates}
        {--limit=0 : Only process this many listings (0 = all)}';

    protected $description = 'Backfill listing latitude/longitude by geocoding their address via OpenStreetMap Nominatim.';

    // Alibaug taluka sanity bounds — a geocode result outside this box is rejected.
    private const LAT_MIN = 18.30;
    private const LAT_MAX = 18.90;
    private const LNG_MIN = 72.70;
    private const LNG_MAX = 73.10;

    // A geocode result is only trusted if it lands within this many km of the
    // listing's hand-curated area centroid — otherwise it's an ambiguous match
    // (e.g. a same-named village in another taluka) and we keep the area fallback.
    private const MAX_KM_FROM_AREA = 6.0;

    // Nominatim usage policy: max 1 request/second, descriptive User-Agent required.
    private const ENDPOINT = 'https://nominatim.openstreetmap.org/search';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $query = Listing::query()->with('area:id,name,latitude,longitude')->orderBy('id');

        if (! $this->option('all')) {
            $query->where(fn ($q) => $q->whereNull('latitude')->orWhereNull('longitude'));
        }

        if (($limit = (int) $this->option('limit')) > 0) {
            $query->limit($limit);
        }

        $listings = $query->get();

        if ($listings->isEmpty()) {
            $this->info('No listings need geocoding.');
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Geocoding {$listings->count()} listing(s) (1 req/sec, be patient)…");

        $set = 0;
        $skipped = 0;

        foreach ($listings as $listing) {
            $candidates = $this->buildQueryCandidates($listing);

            if (empty($candidates)) {
                $this->warn("  – #{$listing->id} \"{$listing->title}\": no address or area to geocode, skipped");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  would try #{$listing->id}: " . implode('  |  ', $candidates));
                continue;
            }

            // Anchor for validating results: the listing's own trusted area centroid.
            $anchorLat = $listing->area?->latitude !== null ? (float) $listing->area->latitude : null;
            $anchorLng = $listing->area?->longitude !== null ? (float) $listing->area->longitude : null;

            $coords = null;
            foreach ($candidates as $queryText) {
                $coords = $this->geocode($queryText, $anchorLat, $anchorLng);
                usleep(1_100_000); // Respect Nominatim's 1 req/sec policy between every call.
                if ($coords !== null) {
                    break;
                }
            }

            if ($coords === null) {
                $this->warn("  – #{$listing->id} \"{$listing->title}\": no confident result, left for area fallback");
                $skipped++;
            } else {
                $listing->update(['latitude' => $coords[0], 'longitude' => $coords[1]]);
                $this->line("  ✓ #{$listing->id} \"{$listing->title}\" → {$coords[0]}, {$coords[1]}");
                $set++;
            }
        }

        $this->newLine();
        $this->info("Done. Set: {$set}, skipped: {$skipped}.");

        if ($set > 0) {
            $this->call('cache:forget', ['key' => 'map.markers.approved']);
        }

        return self::SUCCESS;
    }

    /**
     * Build an ordered list of geocoder queries, most specific first.
     * A specific street address can refine beyond the area centroid; the
     * area-only query is the last resort before giving up.
     */
    private function buildQueryCandidates(Listing $listing): array
    {
        $address = trim((string) $listing->address);
        $area = $listing->area?->name;
        $suffix = 'Alibag, Raigad, Maharashtra, India';

        $candidates = [];
        if ($address !== '') {
            $candidates[] = $address;                  // as entered (often already includes locality)
            $candidates[] = "{$address}, {$suffix}";   // anchored to the taluka
        }
        if (! empty($area)) {
            $candidates[] = "{$area}, {$suffix}";      // area-level fallback
        }

        // De-duplicate while preserving order.
        return array_values(array_unique($candidates));
    }

    /**
     * Query Nominatim (viewbox as a bias, not a hard bound) and return [lat, lng]
     * only if the result is inside the Alibaug box AND close to the trusted area
     * centroid. Otherwise null — an ambiguous match we don't want to trust.
     */
    private function geocode(string $queryText, ?float $anchorLat, ?float $anchorLng): ?array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders(['User-Agent' => 'HelloAlibaug/1.0 (+https://helloalibaug.com; listings geocoder)'])
                ->retry(2, 1000)
                ->get(self::ENDPOINT, [
                    'q' => $queryText,
                    'format' => 'json',
                    'limit' => 1,
                    'countrycodes' => 'in',
                    // viewbox biases results toward Alibaug without hard-excluding
                    // (bounded=1 returns nothing when the exact string doesn't match).
                    'viewbox' => self::LNG_MIN . ',' . self::LAT_MAX . ',' . self::LNG_MAX . ',' . self::LAT_MIN,
                ]);

            if (! $response->successful()) {
                return null;
            }

            $hit = $response->json(0);
            if (! $hit || ! isset($hit['lat'], $hit['lon'])) {
                return null;
            }

            $lat = (float) $hit['lat'];
            $lng = (float) $hit['lon'];

            // Reject anything outside the Alibaug box (bad match).
            if ($lat < self::LAT_MIN || $lat > self::LAT_MAX || $lng < self::LNG_MIN || $lng > self::LNG_MAX) {
                return null;
            }

            // Reject anything too far from the listing's trusted area centroid —
            // guards against same-named villages in another taluka.
            if ($anchorLat !== null && $anchorLng !== null) {
                if ($this->haversineKm($lat, $lng, $anchorLat, $anchorLng) > self::MAX_KM_FROM_AREA) {
                    return null;
                }
            }

            return [round($lat, 7), round($lng, 7)];
        } catch (\Throwable $e) {
            $this->warn("    geocode error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Great-circle distance in kilometres between two lat/lng points.
     */
    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

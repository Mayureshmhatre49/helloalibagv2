<?php

namespace App\Console\Commands;

use App\Models\Listing;
use Illuminate\Console\Command;

class ListingCoordsReport extends Command
{
    protected $signature = 'listings:coords-report
        {--approved : Only check approved listings (the ones actually on the public map)}
        {--far=6.0 : Flag listings this many km or more from their area centroid}
        {--id= : Inspect one specific listing id in detail}';

    protected $description = 'Report where each listing\'s map pin comes from — a real coordinate or the area-centroid fallback — and flag pins that look misplaced.';

    public function handle(): int
    {
        $far = (float) $this->option('far');

        $query = Listing::query()->with(['area:id,name,latitude,longitude', 'category:id,name']);

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        if ($this->option('approved')) {
            $query->where('status', 'approved');
        }

        $listings = $query->orderBy('id')->get();

        if ($listings->isEmpty()) {
            $this->warn('No listings matched.');
            return self::SUCCESS;
        }

        $pinned = 0;
        $fallback = 0;
        $suspicious = [];
        $noAnchor = 0;

        foreach ($listings as $listing) {
            $hasCoords = $listing->latitude !== null && $listing->longitude !== null;

            if (! $hasCoords) {
                $fallback++;

                if ($this->option('id')) {
                    $this->line("#{$listing->id} \"{$listing->title}\"");
                    $this->warn('  No stored coordinates — the map scatters this pin ~600m around the ' . ($listing->area?->name ?? 'default') . ' area centre.');
                    $this->line('  Fix: pin it on the listing edit form, or run `php artisan listings:geocode`.');
                }

                continue;
            }

            $pinned++;

            $anchorLat = $listing->area?->latitude !== null ? (float) $listing->area->latitude : null;
            $anchorLng = $listing->area?->longitude !== null ? (float) $listing->area->longitude : null;

            if ($anchorLat === null || $anchorLng === null) {
                $noAnchor++;
                continue;
            }

            $km = $this->haversineKm((float) $listing->latitude, (float) $listing->longitude, $anchorLat, $anchorLng);

            if ($this->option('id')) {
                $this->line("#{$listing->id} \"{$listing->title}\"");
                $this->line('  Coordinates : ' . $listing->latitude . ', ' . $listing->longitude);
                $this->line('  Area        : ' . ($listing->area?->name ?? '—'));
                $this->line('  Distance from area centre: ' . round($km, 2) . ' km');
                $this->line('  Google Maps : https://www.google.com/maps?q=' . $listing->latitude . ',' . $listing->longitude);
                $km >= $far
                    ? $this->warn('  ⚠ Further from its area than expected — worth eyeballing on the map above.')
                    : $this->info('  ✓ Sits within the expected range of its area.');
            }

            if ($km >= $far) {
                $suspicious[] = [$listing->id, \Illuminate\Support\Str::limit($listing->title, 34), $listing->area?->name ?? '—', round($km, 1) . ' km'];
            }
        }

        if ($this->option('id')) {
            return self::SUCCESS;
        }

        $total = $listings->count();
        $this->newLine();
        $this->info("Checked {$total} listing(s)" . ($this->option('approved') ? ' (approved only)' : '') . ':');
        $this->line("  Real coordinates (owner-pinned or geocoded) : {$pinned}");
        $this->line("  Falling back to area-centroid scatter       : {$fallback}");

        if ($noAnchor > 0) {
            $this->line("  Couldn't be distance-checked (area has no centroid): {$noAnchor}");
        }

        if ($fallback > 0) {
            $this->newLine();
            $this->warn("{$fallback} listing(s) have no real coordinates — their map pins are approximate.");
            $this->line('Run `php artisan listings:geocode` to derive them from the address.');
        }

        if (! empty($suspicious)) {
            $this->newLine();
            $this->warn('Pins sitting ' . $far . 'km+ from their area centre — check these on a map:');
            $this->table(['ID', 'Title', 'Area', 'Distance'], $suspicious);
            $this->line('Inspect one with: php artisan listings:coords-report --id=<ID>');
        } elseif ($pinned > 0) {
            $this->newLine();
            $this->info('No misplaced pins detected — every stored coordinate sits near its area.');
        }

        return self::SUCCESS;
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

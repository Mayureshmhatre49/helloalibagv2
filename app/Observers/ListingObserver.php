<?php

namespace App\Observers;

use App\Models\Listing;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ListingObserver
{
    /**
     * Handle the Listing "deleting" event.
     *
     * The listing_images rows are removed by the database cascade, but that
     * leaves the actual files orphaned on disk forever. Delete them here —
     * in the observer rather than in each controller — so every deletion
     * path is covered, including admin, owner and tinker.
     */
    public function deleting(Listing $listing): void
    {
        foreach ($listing->images as $image) {
            foreach ([$image->path, $image->thumbnail] as $path) {
                if (empty($path) || str_starts_with($path, 'http')) {
                    continue; // externally-hosted image — nothing of ours to delete
                }

                try {
                    Storage::disk('public')->delete(ltrim(preg_replace('#^/?storage/#', '', $path), '/'));
                } catch (\Throwable $e) {
                    // Never let cleanup block the delete the user asked for.
                    Log::warning("Failed to delete listing image file [{$path}]: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Handle the Listing "created" event.
     */
    public function created(Listing $listing): void
    {
        $this->onChange();
    }

    /**
     * Handle the Listing "updated" event.
     */
    public function updated(Listing $listing): void
    {
        $this->onChange();
    }

    /**
     * Handle the Listing "deleted" event.
     */
    public function deleted(Listing $listing): void
    {
        $this->onChange();
    }

    /**
     * Handle the Listing "restored" event.
     */
    public function restored(Listing $listing): void
    {
        $this->onChange();
    }

    /**
     * Handle the Listing "force deleted" event.
     */
    public function forceDeleted(Listing $listing): void
    {
        $this->onChange();
    }

    /**
     * Any listing mutation (coordinates, images, price, status) invalidates the
     * cached map markers and regenerates the sitemap.
     *
     * Sitemap generation queries the whole catalog and writes to disk — too
     * expensive to run synchronously on every mutation (including a simple
     * view-count increment on every page load). Throttle it to at most once
     * every 10 minutes; Cache::add() is atomic so concurrent requests can't
     * both win the lock and run it twice.
     */
    private function onChange(): void
    {
        Cache::forget('map.markers.approved');

        if (Cache::add('sitemap:regenerate-lock', true, now()->addMinutes(10))) {
            Artisan::call('sitemap:generate');
        }
    }
}

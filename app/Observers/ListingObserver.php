<?php

namespace App\Observers;

use App\Models\Listing;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ListingObserver
{
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

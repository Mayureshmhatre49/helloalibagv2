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
     */
    private function onChange(): void
    {
        Cache::forget('map.markers.approved');
        Artisan::call('sitemap:generate');
    }
}

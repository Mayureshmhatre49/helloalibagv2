<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->onChange();
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->onChange();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->onChange();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        $this->onChange();
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        $this->onChange();
    }

    /**
     * Any category mutation invalidates the cached header/footer nav list and
     * changes the set of URLs in the sitemap.
     *
     * Sitemap generation queries the whole catalog and writes to disk, so it's
     * throttled to at most once every 10 minutes — Cache::add() is atomic, so
     * concurrent requests can't both win the lock and run it twice. The daily
     * scheduled sitemap:generate remains the consistency backstop.
     */
    private function onChange(): void
    {
        Cache::forget(Category::NAV_CACHE_KEY);

        if (Cache::add('sitemap:regenerate-lock', true, now()->addMinutes(10))) {
            Artisan::call('sitemap:generate');
        }
    }
}

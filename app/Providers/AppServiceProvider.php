<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Listing;
use App\Models\Category;
use App\Observers\ListingObserver;
use App\Observers\CategoryObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Listing::observe(ListingObserver::class);
        Category::observe(CategoryObserver::class);

        // The shared layout renders the active categories twice (header nav and
        // footer). Resolving them here means one cached lookup per request
        // instead of two DB queries on every single page view. Invalidated by
        // CategoryObserver, so the long TTL never serves stale categories.
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', Cache::remember(
                Category::NAV_CACHE_KEY,
                now()->addDay(),
                fn () => Category::where('is_active', true)->orderBy('sort_order')->get()
            ));
        });
    }
}

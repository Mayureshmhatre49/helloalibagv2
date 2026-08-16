<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Listing;
use App\Models\Category;
use App\Observers\ListingObserver;
use App\Observers\CategoryObserver;
use App\Services\MapApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton so the map_api_settings row is fetched once per request,
        // no matter how many places (controllers, nav partials) check it.
        $this->app->singleton(MapApiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Listing::observe(ListingObserver::class);
        Category::observe(CategoryObserver::class);

        // Force canonical apex URL & HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }

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
            $view->with('mapEnabled', app(MapApiService::class)->isEnabled());
        });
    }
}

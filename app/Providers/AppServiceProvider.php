<?php

namespace App\Providers;

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
    }
}

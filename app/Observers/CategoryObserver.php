<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Artisan;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        Artisan::call('sitemap:generate');
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        Artisan::call('sitemap:generate');
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        Artisan::call('sitemap:generate');
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        Artisan::call('sitemap:generate');
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        Artisan::call('sitemap:generate');
    }
}

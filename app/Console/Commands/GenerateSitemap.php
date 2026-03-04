<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml for the application';

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Homepage
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Categories
        Category::where('is_active', true)->get()->each(function ($category) use ($sitemap) {
            $sitemap->add(Url::create(route('category.show', $category))
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($category->updated_at));
        });

        // Approved Listings
        Listing::approved()->with('category')->get()->each(function ($listing) use ($sitemap) {
            $sitemap->add(Url::create(route('listing.show', [$listing->category->slug, $listing->slug]))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($listing->updated_at));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml');

        return self::SUCCESS;
    }
}

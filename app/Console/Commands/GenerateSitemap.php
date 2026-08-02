<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Category;
use App\Models\Guide;
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

        // Evergreen utility pages
        foreach (['weather.index', 'map.index', 'page.how-to-reach', 'page.ferry-schedule', 'page.beaches', 'page.local-markets', 'page.emergency', 'guides.index'] as $name) {
            $sitemap->add(Url::create(route($name))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        }

        // Categories
        Category::where('is_active', true)->get()->each(function ($category) use ($sitemap) {
            $sitemap->add(Url::create(route('category.show', $category))
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($category->updated_at));
        });

        // Areas
        Area::where('is_active', true)->get()->each(function ($area) use ($sitemap) {
            $sitemap->add(Url::create(route('area.show', $area))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($area->updated_at));
        });

        // Editorial Guides
        Guide::published()->get()->each(function ($guide) use ($sitemap) {
            $sitemap->add(Url::create(route('guides.show', $guide))
                ->setPriority(0.9)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setLastModificationDate($guide->updated_at));
        });

        // Approved Listings
        Listing::approved()->with('category')->get()->each(function ($listing) use ($sitemap) {
            $sitemap->add(Url::create(route('listing.show', [$listing->category->slug, $listing->slug]))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($listing->updated_at));
        });

        // Programmatic landing pages — every category × area combination that has
        // at least one approved listing. Plus Alibaug-wide + price-bucket variants.
        $this->addLandingPages($sitemap);

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml');

        return self::SUCCESS;
    }

    private function addLandingPages(Sitemap $sitemap): void
    {
        $categories = Category::where('is_active', true)->get();
        $areas = Area::where('is_active', true)->get();

        foreach ($categories as $cat) {
            // Alibaug-wide
            $sitemap->add(Url::create(url('/' . $cat->slug . '-in-alibaug'))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

            foreach ($areas as $area) {
                $hasListing = Listing::approved()
                    ->where('category_id', $cat->id)
                    ->where('area_id', $area->id)
                    ->exists();
                if (!$hasListing) {
                    continue;
                }
                $sitemap->add(Url::create(url('/' . $cat->slug . '-in-' . $area->slug))
                    ->setPriority(0.6)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }

        // Price-bucket variants only for "stay" (the highest-intent category).
        $stay = $categories->firstWhere('slug', 'stay');
        if ($stay) {
            foreach (['budget', 'luxury', 'mid-range'] as $bucket) {
                $sitemap->add(Url::create(url('/' . $bucket . '-villas-in-alibaug'))
                    ->setPriority(0.65)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }
    }
}

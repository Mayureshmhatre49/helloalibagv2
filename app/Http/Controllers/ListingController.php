<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\ListingService;

class ListingController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function show(string $category, string $slug)
    {
        $listing = $this->listingService->getListingBySlug($slug);

        if (!$listing) {
            abort(404);
        }

        $listing->incrementViews();

        // Handle Recently Viewed session
        $recentlyViewedIds = session()->get('recently_viewed', []);
        
        // Remove current listing from history to prepend it, enforcing unique queue
        if (($key = array_search($listing->id, $recentlyViewedIds)) !== false) {
            unset($recentlyViewedIds[$key]);
        }
        
        array_unshift($recentlyViewedIds, $listing->id);
        // Keep only top 8
        $recentlyViewedIds = array_slice($recentlyViewedIds, 0, 8);
        session()->put('recently_viewed', $recentlyViewedIds);

        // Fetch the recently viewed models excluding the current one
        $recentlyViewed = empty($recentlyViewedIds) ? collect() : Listing::whereIn('id', $recentlyViewedIds)
            ->where('id', '!=', $listing->id)
            ->approved()
            ->with(['images', 'area'])
            ->get()
            ->sortBy(fn($model) => array_search($model->id, $recentlyViewedIds));

        $relatedListings = Listing::approved()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->with(['images', 'area'])
            ->limit(4)
            ->get();

        return view('listing.show', compact('listing', 'relatedListings', 'recentlyViewed'));
    }
}

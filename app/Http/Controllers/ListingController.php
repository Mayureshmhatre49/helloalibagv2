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

        $relatedListings = Listing::approved()
            ->where('category_id', $listing->category_id)
            ->where('id', '!=', $listing->id)
            ->with(['images', 'area'])
            ->limit(4)
            ->get();

        return view('listing.show', compact('listing', 'relatedListings'));
    }
}

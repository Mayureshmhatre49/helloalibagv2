<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Inquiry;
use App\Models\Review;
use App\Services\ListingService;

class DashboardController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function index()
    {
        $user = auth()->user();
        $listingIds = Listing::where('created_by', $user->id)->pluck('id');

        $totalListings = $listingIds->count();
        $approvedListings = Listing::where('created_by', $user->id)->where('status', 'approved')->count();
        $pendingListings = Listing::where('created_by', $user->id)->where('status', 'pending')->count();
        $totalViews = Listing::where('created_by', $user->id)->sum('views_count');

        // Analytics data
        $totalInquiries = Inquiry::whereIn('listing_id', $listingIds)->count();
        $newInquiries = Inquiry::whereIn('listing_id', $listingIds)->where('status', 'new')->count();
        $totalReviews = Review::whereIn('listing_id', $listingIds)->where('status', 'approved')->count();
        $avgRating = Review::whereIn('listing_id', $listingIds)->where('status', 'approved')->avg('rating');

        // Top performing listing
        $topListing = Listing::where('created_by', $user->id)
            ->where('status', 'approved')
            ->orderBy('views_count', 'desc')
            ->first();

        // Recent inquiries
        $recentInquiries = Inquiry::whereIn('listing_id', $listingIds)
            ->with('listing')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalListings', 'approvedListings', 'pendingListings', 'totalViews',
            'totalInquiries', 'newInquiries', 'totalReviews', 'avgRating',
            'topListing', 'recentInquiries'
        ));
    }
}

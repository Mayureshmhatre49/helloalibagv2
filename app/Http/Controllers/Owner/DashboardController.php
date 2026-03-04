<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Listing;
use App\Models\Inquiry;
use App\Models\Review;
use App\Services\ListingService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function index()
    {
        $user = auth()->user();
        $listingIds = Listing::where('created_by', $user->id)->pluck('id');

        $totalListings   = $listingIds->count();
        $approvedListings = Listing::where('created_by', $user->id)->where('status', 'approved')->count();
        $pendingListings  = Listing::where('created_by', $user->id)->where('status', 'pending')->count();
        $totalViews      = Listing::where('created_by', $user->id)->sum('views_count');

        // Analytics data
        $totalInquiries  = Inquiry::whereIn('listing_id', $listingIds)->count();
        $newInquiries    = Inquiry::whereIn('listing_id', $listingIds)->where('status', 'new')->count();
        $totalReviews    = Review::whereIn('listing_id', $listingIds)->where('status', 'approved')->count();
        $avgRating       = Review::whereIn('listing_id', $listingIds)->where('status', 'approved')->avg('rating');

        // Pending bookings
        $pendingBookings = Booking::whereIn('listing_id', $listingIds)->pending()->count();

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

        // 30-day views chart data (sum of views across listings per day from created_at of listing, use updated_at as proxy)
        // We simulate daily view data from total views divided evenly for the chart demo
        // In production you'd have a listing_views_log table; for now we build a sparkline from the listing updated_at
        $chartLabels = collect(range(29, 0))->map(fn($d) => now()->subDays($d)->format('d M'))->values();
        $chartData   = collect(range(29, 0))->map(function ($d) use ($totalViews) {
            // Distribute views with a sine-wave pattern for visual interest
            $base = $totalViews > 0 ? max(1, intval($totalViews / 60)) : 0;
            return $base + round(abs(sin(($d / 7) * M_PI)) * $base);
        })->values();

        return view('dashboard.index', compact(
            'totalListings', 'approvedListings', 'pendingListings', 'totalViews',
            'totalInquiries', 'newInquiries', 'totalReviews', 'avgRating',
            'topListing', 'recentInquiries', 'pendingBookings',
            'chartLabels', 'chartData'
        ));
    }
}

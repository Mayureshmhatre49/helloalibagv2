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

        // Real 30-day view trend, summed across this owner's listings. Days with
        // no recorded views are filled with 0 so the axis stays continuous.
        $windowStart = now()->subDays(29)->startOfDay();

        $dailyViews = DB::table('listing_view_logs')
            ->whereIn('listing_id', $listingIds)
            ->where('viewed_on', '>=', $windowStart->toDateString())
            ->groupBy('viewed_on')
            ->pluck(DB::raw('SUM(views)'), 'viewed_on');

        $days = collect(range(29, 0))->map(fn ($d) => now()->subDays($d)->startOfDay());

        $chartLabels = $days->map(fn ($day) => $day->format('d M'))->values();
        $chartData = $days->map(fn ($day) => (int) ($dailyViews[$day->toDateString()] ?? 0))->values();

        // View tracking started when the listing_view_logs table was added, so
        // an owner with lifetime views but nothing in the window is showing an
        // empty chart legitimately — let the view explain that rather than
        // implying nobody visited.
        $hasViewHistory = $chartData->sum() > 0;

        return view('dashboard.index', compact(
            'totalListings', 'approvedListings', 'pendingListings', 'totalViews',
            'totalInquiries', 'newInquiries', 'totalReviews', 'avgRating',
            'topListing', 'recentInquiries', 'pendingBookings',
            'chartLabels', 'chartData', 'hasViewHistory'
        ));
    }
}

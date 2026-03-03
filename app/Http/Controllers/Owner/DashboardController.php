<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Services\ListingService;

class DashboardController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function index()
    {
        $user = auth()->user();
        $totalListings = Listing::where('created_by', $user->id)->count();
        $approvedListings = Listing::where('created_by', $user->id)->where('status', 'approved')->count();
        $pendingListings = Listing::where('created_by', $user->id)->where('status', 'pending')->count();
        $totalViews = Listing::where('created_by', $user->id)->sum('views_count');

        return view('dashboard.index', compact('totalListings', 'approvedListings', 'pendingListings', 'totalViews'));
    }
}

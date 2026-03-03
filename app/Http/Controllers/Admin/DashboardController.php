<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Review;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalListings = Listing::count();
        $pendingListings = Listing::pending()->count();
        $approvedListings = Listing::approved()->count();
        $premiumListings = Listing::where('is_premium', true)->count();
        
        $totalUsers = User::count();
        $totalOwners = User::whereHas('role', function($q) { $q->where('slug', 'owner'); })->count();
        
        $pendingReviews = Review::pending()->count();
        
        $recentListings = Listing::with(['category', 'creator'])->latest()->take(5)->get();
        
        // Activity Feed: Combined recent signups and listings
        $recentUsers = User::with('role')->latest()->take(5)->get()->map(function($user) {
            return [
                'type' => 'user',
                'title' => "New " . ($user->role->name ?? 'User') . " Registered",
                'description' => $user->name . " (" . $user->email . ")",
                'time' => $user->created_at,
                'icon' => 'person_add',
                'color' => 'text-blue-500'
            ];
        });

        $listingActivity = Listing::latest()->take(5)->get()->map(function($listing) {
            return [
                'type' => 'listing',
                'title' => "New Listing Submitted",
                'description' => $listing->title,
                'time' => $listing->created_at,
                'icon' => 'add_business',
                'color' => 'text-emerald-500'
            ];
        });

        $activityFeed = $recentUsers->concat($listingActivity)->sortByDesc('time')->take(8);

        return view('admin.dashboard', compact(
            'totalListings', 'pendingListings', 'approvedListings', 'premiumListings',
            'totalUsers', 'totalOwners', 'pendingReviews', 'recentListings', 'activityFeed'
        ));
    }
}

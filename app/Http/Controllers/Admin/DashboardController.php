<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Review;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\SupportTicket;

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
        $totalInquiries = Inquiry::count();
        $openTickets = SupportTicket::active()->count();
        $totalViews = Listing::sum('views_count');
        
        $recentListings = Listing::with(['category', 'creator'])->latest()->take(5)->get();
        
        // Activity Feed: Combined recent signups, listings, and inquiries
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

        $inquiryActivity = Inquiry::with('listing')->latest()->take(3)->get()->map(function($inquiry) {
            return [
                'type' => 'inquiry',
                'title' => "New Inquiry Received",
                'description' => $inquiry->name . " → " . ($inquiry->listing->title ?? 'Listing'),
                'time' => $inquiry->created_at,
                'icon' => 'mail',
                'color' => 'text-purple-500'
            ];
        });

        $activityFeed = $recentUsers->concat($listingActivity)->concat($inquiryActivity)->sortByDesc('time')->take(10);

        // --- Chart Data Aggregation ---
        
        // 1. Listings & Users Growth (Last 6 Months)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $months->push(now()->subMonths($i)->format('M y'));
        }

        $listingGrowth = [];
        $userGrowth = [];

        foreach ($months as $month) {
            $startDate = \Carbon\Carbon::createFromFormat('M y', $month)->startOfMonth();
            $endDate = \Carbon\Carbon::createFromFormat('M y', $month)->endOfMonth();

            $listingGrowth[] = Listing::whereBetween('created_at', [$startDate, $endDate])->count();
            $userGrowth[] = User::whereBetween('created_at', [$startDate, $endDate])->count();
        }

        $growthChartData = [
            'labels' => $months->toArray(),
            'listings' => $listingGrowth,
            'users' => $userGrowth
        ];

        // 2. Inquiry Volume (Last 30 Days)
        $inquiryDates = collect();
        $inquiryCounts = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $inquiryDates->push($date->format('d M'));
            $inquiryCounts[] = Inquiry::whereDate('created_at', $date->toDateString())->count();
        }

        $inquiryChartData = [
            'labels' => $inquiryDates->toArray(),
            'series' => $inquiryCounts
        ];

        return view('admin.dashboard', compact(
            'totalListings', 'pendingListings', 'approvedListings', 'premiumListings',
            'totalUsers', 'totalOwners', 'pendingReviews', 'recentListings', 'activityFeed',
            'totalInquiries', 'openTickets', 'totalViews', 'growthChartData', 'inquiryChartData'
        ));
    }
}

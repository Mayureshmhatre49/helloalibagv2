<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\BlogPost;
use App\Models\Listing;
use App\Services\CategoryService;
use App\Services\ListingService;

class HomeController extends Controller
{
    public function __construct(
        protected ListingService $listingService,
        protected CategoryService $categoryService,
    ) {}

    public function index()
    {
        $categories      = $this->categoryService->getCategoryWithCounts();
        $featuredListings = $this->listingService->getFeaturedListings(8);
        $featuredListings->loadCount('approvedReviews');

        $areas = Area::where('is_active', true)->get();

        $recentPosts = BlogPost::where('status', 'published')
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        $totalListings = Listing::where('status', 'approved')->count();
        $totalAreas    = Area::where('is_active', true)->count();

        return view('home', compact(
            'categories',
            'featuredListings',
            'areas',
            'recentPosts',
            'totalListings',
            'totalAreas',
        ));
    }
}

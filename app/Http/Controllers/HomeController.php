<?php

namespace App\Http\Controllers;

use App\Models\Area;
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
        $categories = $this->categoryService->getCategoryWithCounts();
        $featuredListings = $this->listingService->getFeaturedListings(4);
        $areas = Area::where('is_active', true)->get();

        return view('home', compact('categories', 'featuredListings', 'areas'));
    }
}

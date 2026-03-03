<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Area;
use App\Models\Category;
use App\Services\ListingService;

class CategoryController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function show(Category $category)
    {
        $filters = request()->only(['search', 'area_id', 'min_price', 'max_price', 'amenities', 'sort']);
        $listings = $this->listingService->getListingsByCategory($category, $filters);
        $areas = Area::where('is_active', true)->get();
        $amenities = Amenity::orderBy('sort_order')->get();

        return view('category.show', compact('category', 'listings', 'areas', 'amenities', 'filters'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Amenity;
use App\Services\SearchService;
use App\Services\CategoryService;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService,
        protected CategoryService $categoryService,
    ) {}

    public function index()
    {
        $query = request('q', '');
        $filters = request()->only(['category_id', 'area_id', 'min_price', 'max_price', 'amenities', 'sort']);

        $results = $this->searchService->search($query, $filters);
        $categories = $this->categoryService->getAllActive();
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $amenities = Amenity::orderBy('sort_order')->get();

        return view('search.index', compact('results', 'query', 'categories', 'filters', 'areas', 'amenities'));
    }
}

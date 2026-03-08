<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Services\SearchService;

class AreaController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    public function show(Area $area)
    {
        if (!$area->is_active) {
            abort(404);
        }

        $query = request('q', '');
        $filters = request()->only(['category_id', 'min_price', 'max_price', 'amenities', 'sort']);
        
        // Force the area_id filter to scope the results
        $filters['area_id'] = $area->id;

        $results = $this->searchService->search($query, $filters);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $amenities = \App\Models\Amenity::orderBy('sort_order')->get();

        return view('area.show', compact('area', 'results', 'query', 'categories', 'filters', 'areas', 'amenities'));
    }
}

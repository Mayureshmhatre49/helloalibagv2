<?php

namespace App\Http\Controllers;

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
        $filters = request()->only(['category_id', 'area_id', 'min_price', 'max_price']);

        $results = $query ? $this->searchService->search($query, $filters) : collect();
        $categories = $this->categoryService->getAllActive();

        return view('search.index', compact('results', 'query', 'categories', 'filters'));
    }
}

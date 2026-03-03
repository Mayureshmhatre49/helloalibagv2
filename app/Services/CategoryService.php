<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function getAllActive()
    {
        return Cache::remember('categories.active', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function getBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->firstOrFail();
    }

    public function getCategoryWithCounts()
    {
        return Cache::remember('categories.with_counts', 3600, function () {
            return Category::where('is_active', true)
                ->withCount(['listings' => fn ($q) => $q->where('status', 'approved')])
                ->orderBy('sort_order')
                ->get();
        });
    }
}

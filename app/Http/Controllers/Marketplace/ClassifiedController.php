<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Classified;
use App\Models\ClassifiedCategory;
use Illuminate\Http\Request;

class ClassifiedController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'category', 'area_id', 'min_price', 'max_price', 'condition', 'sort']);

        $query = Classified::active()
            ->with(['category', 'area', 'images']);

        if (!empty($filters['q'])) {
            $query->search($filters['q']);
        }

        $activeCategory = null;
        if (!empty($filters['category'])) {
            $activeCategory = ClassifiedCategory::where('slug', $filters['category'])->first();
            if ($activeCategory) {
                $query->where('classified_category_id', $activeCategory->id);
            }
        }

        if (!empty($filters['area_id'])) {
            $query->where('area_id', $filters['area_id']);
        }
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        if (!empty($filters['condition'])) {
            $query->where('condition', $filters['condition']);
        }

        match ($filters['sort'] ?? 'newest') {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('views_count', 'desc'),
            default => $query->latest(),
        };

        // Featured items float to the top within the chosen sort.
        $query->orderByDesc('is_featured');

        $classifieds = $query->paginate(12)->withQueryString();
        $categories = ClassifiedCategory::active()->orderBy('sort_order')->withCount('activeClassifieds')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();

        return view('marketplace.index', compact('classifieds', 'categories', 'areas', 'filters', 'activeCategory'));
    }

    public function show(Classified $classified)
    {
        // Only show items that are live (or sold, so links don't 404) to the public.
        if (!in_array($classified->status, ['active', 'sold'], true)) {
            abort(404);
        }

        $classified->load(['category', 'area', 'images', 'seller']);
        $classified->incrementViews();

        $moreFromSeller = Classified::active()
            ->where('seller_id', $classified->seller_id)
            ->where('id', '!=', $classified->id)
            ->with(['category', 'images'])
            ->latest()
            ->take(4)
            ->get();

        $relatedItems = Classified::active()
            ->where('classified_category_id', $classified->classified_category_id)
            ->where('id', '!=', $classified->id)
            ->with(['category', 'images'])
            ->latest()
            ->take(4)
            ->get();

        return view('marketplace.show', compact('classified', 'moreFromSeller', 'relatedItems'));
    }
}

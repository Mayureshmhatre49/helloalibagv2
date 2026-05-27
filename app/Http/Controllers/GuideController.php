<?php

namespace App\Http\Controllers;

use App\Models\Guide;

class GuideController extends Controller
{
    public function index()
    {
        $featured = Guide::published()
            ->featured()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $featuredIds = $featured->pluck('id')->all();

        $guides = Guide::published()
            ->when($featuredIds, fn ($q) => $q->whereNotIn('id', $featuredIds))
            ->latest('published_at')
            ->paginate(12);

        return view('guides.index', compact('featured', 'guides'));
    }

    public function show(Guide $guide)
    {
        abort_unless($guide->is_published, 404);

        $guide->load([
            'author',
            'listings' => fn ($q) => $q->where('listings.status', 'approved')
                ->with(['category:id,name,slug,icon', 'area:id,name,slug', 'images' => fn ($i) => $i->orderBy('sort_order')->limit(1)]),
        ]);

        // Lightweight view counter — no need for a precise count.
        $guide->incrementViews();

        $related = Guide::published()
            ->where('id', '!=', $guide->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('guides.show', compact('guide', 'related'));
    }
}

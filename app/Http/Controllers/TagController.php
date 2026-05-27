<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $listings = $tag->listings()
            ->where('listings.status', 'approved')
            ->with(['category', 'area', 'images', 'amenities', 'tags'])
            ->withCount(['approvedReviews'])
            ->latest('listings.created_at')
            ->paginate(12);

        // Other tags to keep visitors browsing related curated collections.
        $otherTags = Tag::where('id', '!=', $tag->id)
            ->whereHas('listings', fn ($q) => $q->where('listings.status', 'approved'))
            ->orderBy('sort_order')
            ->get();

        return view('tag.show', compact('tag', 'listings', 'otherTags'));
    }
}

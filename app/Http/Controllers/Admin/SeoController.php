<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::with('seoMeta')->approved()->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $listings = $query->paginate(20);
        return view('admin.seo.index', compact('listings'));
    }

    public function edit(Listing $listing)
    {
        $listing->load('seoMeta');
        return view('admin.seo.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'og_title' => 'nullable|string|max:100',
            'og_description' => 'nullable|string|max:200',
            'keywords' => 'nullable|string|max:255',
        ]);

        $listing->seoMeta()->updateOrCreate(
            ['model_type' => Listing::class, 'model_id' => $listing->id],
            $validated
        );

        return redirect()->route('admin.seo.index')->with('success', 'SEO updated for "' . $listing->title . '"');
    }
}

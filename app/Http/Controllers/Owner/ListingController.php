<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function index()
    {
        $listings = $this->listingService->getUserListings(auth()->user());

        return view('dashboard.listings.index', compact('listings'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->get();
        $amenities = Amenity::orderBy('sort_order')->get();

        return view('dashboard.listings.create', compact('categories', 'areas', 'amenities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'area_id' => 'nullable|exists:areas,id',
            'description' => 'nullable|string|max:5000',
            'price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'attributes' => 'nullable|array',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $listing = $this->listingService->store($validated, auth()->user());

        // Handle uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => '/storage/' . $path,
                    'alt_text' => $listing->title,
                    'sort_order' => $idx,
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing submitted for approval!');
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->get();
        $amenities = Amenity::orderBy('sort_order')->get();
        $listing->load(['images', 'amenities', 'listingAttributes']);

        return view('dashboard.listings.edit', compact('listing', 'categories', 'areas', 'amenities'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'area_id' => 'nullable|exists:areas,id',
            'description' => 'nullable|string|max:5000',
            'price' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'attributes' => 'nullable|array',
        ]);

        $this->listingService->update($listing, $validated);

        // Handle uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => '/storage/' . $path,
                    'alt_text' => $listing->title,
                    'sort_order' => $listing->images->count() + $idx,
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);
        $listing->delete();

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }
}

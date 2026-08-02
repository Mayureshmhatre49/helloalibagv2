<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\Category;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Tag;
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

    /**
     * Legacy create form. The onboarding wizard is the only supported way to
     * create a listing — it enforces stricter rules (at least one photo, a
     * required area, a 20-character minimum description, per-category required
     * attributes) and runs uploads through ImageService for resizing, WebP
     * conversion and thumbnails. This form did none of that, so a listing made
     * here would be one the wizard would have rejected.
     *
     * Every "Add Listing" link already points at the wizard; these two routes
     * survive only so old bookmarks and any in-flight POST land somewhere
     * sensible instead of 404ing or quietly creating a substandard listing.
     */
    public function create()
    {
        return redirect()->route('owner.onboarding.start');
    }

    public function store(Request $request)
    {
        return redirect()->route('owner.onboarding.start')
            ->with('info', 'Listings are created through this short guided flow — it only takes a minute.');
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->get();
        $amenities = Amenity::orderBy('sort_order')->get();
        $tags = Tag::orderBy('sort_order')->get();
        $listing->load(['images', 'amenities', 'listingAttributes', 'tags']);
        $galleryImages = $listing->images->where('image_type', '!=', 'menu')->values();
        $menuImages    = $listing->images->where('image_type', 'menu')->values();

        return view('dashboard.listings.edit', compact('listing', 'categories', 'areas', 'amenities', 'tags', 'galleryImages', 'menuImages'));
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'attributes' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'menu_images' => 'nullable|array',
            'menu_images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        // Convert multi-checkbox attributes (arrays) to comma-separated strings
        if (!empty($validated['attributes'])) {
            foreach ($validated['attributes'] as $key => $value) {
                if (is_array($value)) {
                    $validated['attributes'][$key] = implode(',', array_filter($value));
                }
            }
        }

        $this->listingService->update($listing, $validated);
        $listing->tags()->sync($request->input('tags', []));

        // Editing resubmits the listing for review — the (possibly changed)
        // content must be re-approved before it goes live again. Any prior
        // "Verified" trust badge is cleared too, since it vouched for the old
        // content, not whatever the owner just changed it to.
        $wasApproved = $listing->status === 'approved';
        $listing->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'approved_at' => null,
            'approved_by' => null,
            'is_verified' => false,
            'verified_at' => null,
            'verification_note' => null,
            'verified_by' => null,
        ]);

        $this->listingService->notifyAdminsOfSubmission($listing, isResubmission: true);

        // Handle uploaded gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => $path,
                    'alt_text' => $listing->title,
                    'sort_order' => $listing->images->count() + $idx,
                    'is_primary' => false,
                    'image_type' => 'gallery',
                ]);
            }
        }

        // Handle uploaded menu images
        if ($request->hasFile('menu_images')) {
            $existingMenuCount = $listing->images->where('image_type', 'menu')->count();
            foreach ($request->file('menu_images') as $idx => $image) {
                $path = $image->store('listings/' . $listing->id . '/menu', 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path' => $path,
                    'alt_text' => $listing->title . ' - Menu',
                    'sort_order' => $existingMenuCount + $idx,
                    'is_primary' => false,
                    'image_type' => 'menu',
                ]);
            }
        }

        $addedCount = ($request->hasFile('images') ? count($request->file('images')) : 0)
                    + ($request->hasFile('menu_images') ? count($request->file('menu_images')) : 0);

        $notice = $wasApproved
            ? 'Listing updated and resubmitted for review — it will go live again once an admin approves the changes.'
            : 'Listing updated and submitted for review.';

        return redirect()->back()
            ->with('success', $notice . ($addedCount > 0 ? " {$addedCount} new photo(s) added." : ''));
    }

    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);
        $listing->delete();

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }
}

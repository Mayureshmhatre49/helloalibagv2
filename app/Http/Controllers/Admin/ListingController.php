<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request)
    {
        // When searching from the top bar, span all statuses by default.
        $status = $request->get('status', $request->filled('q') ? 'all' : 'pending');
        $query = Listing::with(['category', 'area', 'creator', 'images']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($q = $request->get('q')) {
            $query->where('title', 'like', '%' . $q . '%');
        }

        $listings = $query->latest()->paginate(20)->withQueryString();

        return view('admin.listings.index', compact('listings', 'status'));
    }

    public function approve(Listing $listing)
    {
        $this->listingService->approve($listing, auth()->user());

        return redirect()->back()->with('success', 'Listing approved successfully!');
    }

    public function reject(Request $request, Listing $listing)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);
        $this->listingService->reject($listing, $request->rejection_reason);

        return redirect()->back()->with('success', 'Listing rejected with reason.');
    }

    public function toggleFeatured(Listing $listing)
    {
        $listing->update(['is_featured' => !$listing->is_featured]);

        return redirect()->back()->with('success', 'Featured status toggled.');
    }

    public function togglePremium(Listing $listing)
    {
        $listing->update(['is_premium' => !$listing->is_premium]);

        return redirect()->back()->with('success', 'Premium status toggled.');
    }

    /**
     * Mark a listing as verified (or revoke). Verification is a trust signal
     * — only admins flip this. Stores the admin who did it + a timestamp + an
     * optional note for the audit trail.
     */
    public function toggleVerified(\Illuminate\Http\Request $request, Listing $listing)
    {
        if ($listing->is_verified) {
            $listing->update([
                'is_verified' => false,
                'verified_at' => null,
                'verification_note' => null,
                'verified_by' => null,
            ]);
            return redirect()->back()->with('success', "Verification revoked for “{$listing->title}”.");
        }

        $data = $request->validate([
            'verification_note' => 'nullable|string|max:255',
        ]);

        $listing->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verification_note' => $data['verification_note'] ?? null,
            'verified_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "“{$listing->title}” marked as Verified.");
    }

    /**
     * Show the admin edit form for a listing.
     * Admins can edit any listing regardless of ownership.
     */
    public function edit(Listing $listing)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->get();
        $amenities = Amenity::orderBy('sort_order')->get();
        $tags = Tag::orderBy('sort_order')->get();
        $listing->load(['images', 'amenities', 'listingAttributes', 'tags']);
        $galleryImages = $listing->images->where('image_type', '!=', 'menu')->values();
        $menuImages    = $listing->images->where('image_type', 'menu')->values();

        return view('admin.listings.edit', compact('listing', 'categories', 'areas', 'amenities', 'tags', 'galleryImages', 'menuImages'));
    }

    /**
     * Update a listing as an admin.
     * Unlike the owner update, admin edits do NOT reset status to pending —
     * admins are trusted to make corrections without needing re-approval.
     */
    public function update(Request $request, Listing $listing)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'area_id'      => 'nullable|exists:areas,id',
            'description'  => 'nullable|string|max:5000',
            'price'        => 'nullable|numeric|min:0',
            'address'      => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'phone'        => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:255',
            'website'      => 'nullable|url|max:255',
            'whatsapp'     => 'nullable|string|max:20',
            'amenities'    => 'nullable|array',
            'amenities.*'  => 'exists:amenities,id',
            'attributes'   => 'nullable|array',
            'tags'         => 'nullable|array',
            'tags.*'       => 'exists:tags,id',
            'images'       => 'nullable|array',
            'images.*'     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'menu_images'  => 'nullable|array',
            'menu_images.*'=> 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
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

        // Handle uploaded gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('listings/' . $listing->id, 'public');
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'path'       => $path,
                    'alt_text'   => $listing->title,
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
                    'path'       => $path,
                    'alt_text'   => $listing->title . ' - Menu',
                    'sort_order' => $existingMenuCount + $idx,
                    'is_primary' => false,
                    'image_type' => 'menu',
                ]);
            }
        }

        return redirect()->route('admin.listings.index', ['status' => $listing->fresh()->status])
            ->with('success', "Listing \"{$listing->title}\" updated successfully.");
    }
}

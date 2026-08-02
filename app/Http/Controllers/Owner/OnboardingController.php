<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingSubmitted;
use App\Services\ListingService;

class OnboardingController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function start(Request $request)
    {
        // No limit guard here — the category isn't chosen yet, and real estate
        // (paid/offline) is always allowed. The limit is enforced at submit.
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('owner.onboarding.start', compact('categories'));
    }

    /**
     * Block the onboarding flow when the owner is at their plan's listing cap.
     * Real-estate ($categorySlug) is exempt (paid offline).
     */
    private function guardListingLimit(?string $categorySlug = null)
    {
        $user = auth()->user();

        if ($user && !$user->canCreateListing($categorySlug)) {
            $limit = $user->listingLimit();

            return redirect()->route('owner.dashboard')
                ->with('error', "You've reached your plan's limit of {$limit} listing" . ($limit == 1 ? '' : 's') . ". Upgrade your plan to add more.");
        }

        return null;
    }

    public function processStart(Request $request)
    {
        $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')->where('is_active', true)],
        ]);

        $request->session()->put('onboarding_category_id', $request->category_id);

        return redirect()->route('owner.onboarding.wizard');
    }

    public function wizard(Request $request)
    {
        $categoryId = $request->session()->get('onboarding_category_id');
        if (!$categoryId) {
            return redirect()->route('owner.onboarding.start');
        }

        if ($redirect = $this->guardListingLimit(Category::find($categoryId)?->slug)) {
            return $redirect;
        }

        $category = Category::with(['attributes.values'])->findOrFail($categoryId);

        $amenities = Amenity::where('category', $category->slug)
                             ->orWhere('category', $category->name)
                             ->orWhereNull('category')
                             ->orderBy('sort_order')
                             ->get();

        $areas = \App\Models\Area::where('is_active', true)->orderBy('name')->get();

        return view('owner.onboarding.wizard', compact('category', 'amenities', 'areas'));
    }

    public function submit(Request $request, \App\Services\ImageService $imageService)
    {
        if ($redirect = $this->guardListingLimit(Category::find($request->category_id)?->slug)) {
            return $redirect;
        }

        $request->validate([
            'title'              => 'required|string|max:255',
            'area_id'            => ['required', Rule::exists('areas', 'id')->where('is_active', true)],
            'description'        => 'required|string|min:20',
            'price'              => 'nullable|numeric|min:0',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'google_business_url' => ['nullable', 'url', 'max:255', 'regex:/^https?:\\/\\/([a-z0-9-]+\\.)*(google\\.[a-z.]+|goo\\.gl|g\\.page)\\//i'],
            'address'            => 'nullable|string|max:500',
            'latitude'           => 'nullable|numeric|between:-90,90',
            'longitude'          => 'nullable|numeric|between:-180,180',
            'category_id'        => ['required', Rule::exists('categories', 'id')->where('is_active', true)],
            'images'             => 'required|array|min:1',
            'images.*'           => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'amenities'          => 'nullable|array',
            'amenities.*'        => 'exists:amenities,id',
            'dynamic_attributes' => 'nullable|array',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
        ], [
            'title.required'       => 'Please enter a listing title.',
            'area_id.required'     => 'Please select an area/location.',
            'description.required' => 'Please write a short description.',
            'description.min'      => 'Description must be at least 20 characters.',
            'images.required'      => 'Please upload at least 1 photo.',
            'images.min'           => 'Please upload at least 1 photo.',
        ]);

        $category = Category::with('attributes')->findOrFail($request->category_id);

        // Category-required attributes are marked `required` in the HTML, but a
        // direct POST bypasses that — enforce it server-side too.
        $missingRequired = $category->attributes
            ->where('is_required', true)
            ->reject(fn ($attr) => filled($request->input("dynamic_attributes.{$attr->id}")))
            ->pluck('name');

        if ($missingRequired->isNotEmpty()) {
            return back()->withInput()->withErrors([
                'dynamic_attributes' => 'Please fill in: ' . $missingRequired->implode(', '),
            ]);
        }

        $validAttributeIds = $category->attributes->pluck('id')->all();

        $listing = DB::transaction(function () use ($request, $imageService, $validAttributeIds) {
            $listing              = new Listing();
            $listing->title       = $request->title;
            $listing->category_id = $request->category_id;
            $listing->area_id     = $request->area_id;
            $listing->description = $request->description;
            $listing->price       = $request->price;
            $listing->phone       = $request->phone;
            $listing->email       = $request->email;
            $listing->google_business_url = $request->google_business_url;
            $listing->address     = $request->address;
            $listing->latitude    = $request->filled('latitude') ? $request->latitude : null;
            $listing->longitude   = $request->filled('longitude') ? $request->longitude : null;
            $listing->status      = 'pending';
            $listing->created_by  = $request->user()->id;
            $listing->save();

            if ($request->filled('meta_title') || $request->filled('meta_description')) {
                $listing->seoMeta()->create([
                    'title'       => $request->meta_title ?? $request->title,
                    'description' => $request->meta_description,
                ]);
            }

            if ($request->has('dynamic_attributes')) {
                foreach ($request->dynamic_attributes as $key => $value) {
                    // Only accept keys that are real attributes of this category —
                    // a stale/tampered key would otherwise violate the FK constraint.
                    if (!empty($value) && in_array((int) $key, $validAttributeIds, true)) {
                        $listing->attributeValues()->create([
                            'category_attribute_id' => $key,
                            'value' => is_array($value) ? json_encode($value) : $value,
                        ]);
                    }
                }
            }

            if ($request->has('amenities')) {
                $listing->amenities()->sync($request->amenities);
            }

            if ($request->hasFile('images')) {
                $sortOrder = 0;
                foreach ($request->file('images') as $file) {
                    $imageData = $imageService->store($file, 'listings/' . $listing->id);
                    $listing->images()->create([
                        'path'       => $imageData['path'],
                        'thumbnail'  => $imageData['thumbnail'] ?? null,
                        'is_primary' => $sortOrder === 0,
                        'sort_order' => $sortOrder,
                    ]);
                    $sortOrder++;
                }
            }

            return $listing;
        });

        $request->session()->forget('onboarding_category_id');

        try {
            Mail::to($listing->creator->email)->send(new ListingSubmitted($listing, false));
        } catch (\Exception $e) {
            \Log::warning('Owner listing email failed: ' . $e->getMessage());
        }

        $this->listingService->notifyAdminsOfSubmission($listing);

        $isRealEstate = optional(Category::find($request->category_id))->slug === 'real-estate';
        $message = $isRealEstate
            ? '🏡 Your Real Estate listing has been submitted. It\'s a paid (offline) category — our team will contact you to arrange payment, and it goes live once an admin approves.'
            : '🎉 Your listing has been submitted for review! Approval usually takes 24 hours.';

        return redirect()->route('owner.dashboard')->with('success', $message);
    }
}

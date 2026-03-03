<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingSubmitted;
use App\Models\User;
class OnboardingController extends Controller
{
    public function start(Request $request)
    {
        // If owner already has listings, redirect to dashboard.
        if ($request->user()->listings()->count() > 0) {
            return redirect()->route('owner.dashboard');
        }

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('owner.onboarding.start', compact('categories'));
    }

    public function processStart(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
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

        $category = Category::with(['attributes.values'])->findOrFail($categoryId);

        // Fetch amenities. Filter by category slug if applicable, or just get all for now
        // The Amenity model has a 'category' string column. We assume it matches the Category name or slug.
        $amenities = Amenity::where('category', $category->slug)
                             ->orWhere('category', $category->name)
                             ->orWhereNull('category')
                             ->orderBy('sort_order')
                             ->get();
                             
        // Areas for basic info dropdown
        $areas = \App\Models\Area::where('is_active', true)->orderBy('name')->get();

        return view('owner.onboarding.wizard', compact('category', 'amenities', 'areas'));
    }

    public function submit(Request $request, \App\Services\ImageService $imageService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'dynamic_attributes' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'slug' => 'nullable|string|max:255|unique:listings,slug',
        ]);

        $listing = new Listing();
        $listing->title = $request->title;
        if ($request->filled('slug')) {
            $listing->slug = $request->slug;
        }
        $listing->category_id = $request->category_id;
        $listing->area_id = $request->area_id;
        $listing->description = $request->description;
        $listing->price = $request->price;
        $listing->phone = $request->phone;
        $listing->email = $request->email;
        $listing->status = 'pending';
        $listing->created_by = $request->user()->id;
        $listing->save();

        if ($request->filled('meta_title') || $request->filled('meta_description')) {
            $listing->seoMeta()->create([
                'title' => $request->meta_title ?? $request->title,
                'description' => $request->meta_description,
            ]);
        }

        if ($request->has('dynamic_attributes')) {
            foreach ($request->dynamic_attributes as $key => $value) {
                if (!empty($value)) {
                    $listing->attributeValues()->create([
                        'category_attribute_id' => $key,
                        'value' => is_array($value) ? json_encode($value) : $value
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
                    'path' => $imageData['path'],
                    'thumbnail' => $imageData['thumbnail'] ?? null,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder,
                ]);
                $sortOrder++;
            }
        }

        $request->session()->forget('onboarding_category_id');

        // Send submission emails
        Mail::to($listing->creator->email)->send(new ListingSubmitted($listing, false));
        
        $admins = User::whereHas('role', function($q) {
            $q->where('slug', 'admin');
        })->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ListingSubmitted($listing, true));
        }

        return redirect()->route('owner.dashboard')->with('success', 'Your listing has been submitted for review. Approval usually takes up to 24 hours. For urgent queries contact: support@helloalibaug.com');
    }
}

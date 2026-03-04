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
        $request->validate([
            'title'              => 'required|string|max:255',
            'area_id'            => 'required|exists:areas,id',
            'description'        => 'required|string|min:20',
            'price'              => 'nullable|numeric|min:0',
            'phone'              => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'address'            => 'nullable|string|max:500',
            'category_id'        => 'required|exists:categories,id',
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

        $listing              = new Listing();
        $listing->title       = $request->title;
        $listing->category_id = $request->category_id;
        $listing->area_id     = $request->area_id;
        $listing->description = $request->description;
        $listing->price       = $request->price;
        $listing->phone       = $request->phone;
        $listing->email       = $request->email;
        $listing->address     = $request->address;
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
                if (!empty($value)) {
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

        $request->session()->forget('onboarding_category_id');

        try {
            Mail::to($listing->creator->email)->send(new ListingSubmitted($listing, false));
        } catch (\Exception $e) {
            \Log::warning('Owner listing email failed: ' . $e->getMessage());
        }

        try {
            $admins = User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new ListingSubmitted($listing, true));
            }
        } catch (\Exception $e) {
            \Log::warning('Admin listing email failed: ' . $e->getMessage());
        }

        return redirect()->route('owner.dashboard')
            ->with('success', '🎉 Your listing has been submitted for review! Approval usually takes 24 hours.');
    }
}

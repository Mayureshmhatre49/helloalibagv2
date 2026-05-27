<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\Listing;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class GuideController extends Controller
{
    public function index()
    {
        $guides = Guide::with('author')
            ->withCount('listings')
            ->latest()
            ->paginate(20);

        return view('admin.guides.index', compact('guides'));
    }

    public function create()
    {
        $listings = Listing::approved()->select('id', 'title')->orderBy('title')->get();

        return view('admin.guides.form', [
            'guide' => null,
            'listings' => $listings,
            'attached' => [],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $validated['content'] = Purifier::clean($validated['content'], 'blog');
        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        $wordCount = str_word_count(strip_tags($validated['content']));
        $validated['reading_time'] = max(2, (int) ceil($wordCount / 200));

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('hero_image')) {
            $validated['hero_image'] = $this->storeHeroImage($request->file('hero_image'));
        }

        $guide = Guide::create($validated);

        $this->syncListings($guide, $request);

        return redirect()->route('admin.guides.index')->with('success', "Guide “{$guide->title}” created.");
    }

    public function edit(Guide $guide)
    {
        $listings = Listing::approved()->select('id', 'title')->orderBy('title')->get();
        $attached = $guide->listings()->withPivot(['position', 'blurb'])->get()
            ->mapWithKeys(fn ($l) => [$l->id => ['position' => $l->pivot->position, 'blurb' => $l->pivot->blurb]])
            ->all();

        return view('admin.guides.form', compact('guide', 'listings', 'attached'));
    }

    public function update(Request $request, Guide $guide)
    {
        $validated = $this->validateRequest($request, $guide->id);

        $validated['content'] = Purifier::clean($validated['content'], 'blog');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        $wordCount = str_word_count(strip_tags($validated['content']));
        $validated['reading_time'] = max(2, (int) ceil($wordCount / 200));

        if ($validated['is_published'] && empty($guide->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        } elseif (!$validated['is_published']) {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('hero_image')) {
            $newPath = $this->storeHeroImage($request->file('hero_image'));
            if ($guide->hero_image && file_exists(storage_path('app/public/' . $guide->hero_image))) {
                @unlink(storage_path('app/public/' . $guide->hero_image));
            }
            $validated['hero_image'] = $newPath;
        }

        $guide->update($validated);

        $this->syncListings($guide, $request);

        return redirect()->route('admin.guides.index')->with('success', "Guide “{$guide->title}” updated.");
    }

    public function destroy(Guide $guide)
    {
        if ($guide->hero_image && file_exists(storage_path('app/public/' . $guide->hero_image))) {
            @unlink(storage_path('app/public/' . $guide->hero_image));
        }

        $guide->delete();

        return redirect()->route('admin.guides.index')->with('success', 'Guide deleted.');
    }

    // ── helpers ──

    private function validateRequest(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:guides,slug' . ($ignoreId ? ',' . $ignoreId : ''),
            'intro' => 'nullable|string|max:500',
            'content' => 'required|string',
            'focus_keyword' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'hero_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'hero_image_alt' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);
    }

    private function storeHeroImage(\Illuminate\Http\UploadedFile $file): string
    {
        $detected = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getRealPath());
        if (!\in_array($detected, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            abort(422, 'Invalid image file type.');
        }

        $filename = 'guides/' . uniqid('guide_', true) . '.webp';
        $path = storage_path('app/public/' . $filename);
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        \Image::make($file)
            ->fit(1600, 900)
            ->encode('webp', 88)
            ->save($path);

        return $filename;
    }

    private function syncListings(Guide $guide, Request $request): void
    {
        $ids = (array) $request->input('listing_ids', []);
        $positions = (array) $request->input('listing_positions', []);
        $blurbs = (array) $request->input('listing_blurbs', []);

        $sync = [];
        foreach ($ids as $i => $id) {
            $sync[(int) $id] = [
                'position' => (int) ($positions[$id] ?? $i),
                'blurb' => isset($blurbs[$id]) ? (string) $blurbs[$id] : null,
            ];
        }

        $guide->listings()->sync($sync);
    }
}

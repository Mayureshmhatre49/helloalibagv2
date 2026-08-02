<?php

namespace App\Http\Controllers\Marketplace;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Classified;
use App\Models\ClassifiedCategory;
use App\Models\ClassifiedImage;
use App\Services\ClassifiedService;
use Illuminate\Http\Request;

class SellerClassifiedController extends Controller
{
    public function __construct(protected ClassifiedService $service) {}

    public function index()
    {
        $classifieds = Classified::where('seller_id', auth()->id())
            ->with(['category', 'images'])
            ->latest()
            ->paginate(12);

        return view('marketplace.my', compact('classifieds'));
    }

    public function create()
    {
        $categories = ClassifiedCategory::active()->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();

        return view('marketplace.create', compact('categories', 'areas'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $classified = $this->service->store($validated, auth()->user());
        $this->saveImages($request, $classified);

        return redirect()->route('marketplace.my')
            ->with('success', 'Your item was submitted and is awaiting approval.');
    }

    public function edit(Classified $classified)
    {
        $this->authorizeOwner($classified);

        $categories = ClassifiedCategory::active()->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $classified->load('images');

        return view('marketplace.edit', compact('classified', 'categories', 'areas'));
    }

    public function update(Request $request, Classified $classified)
    {
        $this->authorizeOwner($classified);

        $validated = $this->validateData($request);
        $this->service->update($classified, $validated);
        $this->saveImages($request, $classified);

        return redirect()->route('marketplace.my')
            ->with('success', 'Item updated.');
    }

    public function markSold(Classified $classified)
    {
        $this->authorizeOwner($classified);
        $this->service->markSold($classified);

        return redirect()->back()->with('success', 'Marked as sold. Congrats!');
    }

    public function destroy(Classified $classified)
    {
        $this->authorizeOwner($classified);
        $classified->delete();

        return redirect()->route('marketplace.my')->with('success', 'Item removed.');
    }

    public function destroyImage(ClassifiedImage $image)
    {
        $this->authorizeOwner($image->classified);
        $image->delete();

        return redirect()->back()->with('success', 'Photo removed.');
    }

    private function authorizeOwner(Classified $classified): void
    {
        abort_unless($classified->seller_id === auth()->id() || auth()->user()->isAdmin(), 403);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'classified_category_id' => 'required|exists:classified_categories,id',
            'area_id' => ['nullable', Rule::exists('areas', 'id')->where('is_active', true)],
            'description' => 'nullable|string|max:5000',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'is_negotiable' => 'nullable|boolean',
            'condition' => 'nullable|in:new,like_new,good,fair',
            // Array syntax (not pipe string) — the {7,15} comma would otherwise be
            // parsed as a rule separator and break the pattern.
            'contact_phone' => ['nullable', 'string', 'regex:/^[0-9]{7,15}$/'],
            'contact_whatsapp' => ['nullable', 'string', 'regex:/^[0-9]{7,15}$/'],
            'images' => 'nullable|array|max:8',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'contact_phone.regex' => 'Enter a valid phone number — digits only (7–15 digits).',
            'contact_whatsapp.regex' => 'Enter a valid WhatsApp number — digits only (7–15 digits).',
        ]);
    }

    private function saveImages(Request $request, Classified $classified): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $existing = $classified->images()->count();
        foreach ($request->file('images') as $idx => $image) {
            $path = $image->store('classifieds/' . $classified->id, 'public');
            ClassifiedImage::create([
                'classified_id' => $classified->id,
                'path' => $path,
                'alt_text' => $classified->title,
                'sort_order' => $existing + $idx,
                'is_primary' => $existing === 0 && $idx === 0,
            ]);
        }
    }
}

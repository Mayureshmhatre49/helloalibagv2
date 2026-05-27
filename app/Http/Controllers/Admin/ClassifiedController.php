<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Classified;
use App\Models\ClassifiedCategory;
use App\Models\ClassifiedImage;
use App\Services\ClassifiedService;
use Illuminate\Http\Request;

class ClassifiedController extends Controller
{
    public function __construct(protected ClassifiedService $service) {}

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Classified::with(['category', 'area', 'seller', 'images']);
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        $classifieds = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'pending' => Classified::where('status', 'pending')->count(),
            'active'  => Classified::where('status', 'active')->count(),
            'sold'    => Classified::where('status', 'sold')->count(),
            'expired' => Classified::where('status', 'expired')->count(),
            'rejected'=> Classified::where('status', 'rejected')->count(),
        ];

        return view('admin.classifieds.index', compact('classifieds', 'status', 'counts'));
    }

    public function create()
    {
        $categories = ClassifiedCategory::active()->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();

        return view('admin.classifieds.create', compact('categories', 'areas'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Admin-created items are trusted, so they go live immediately.
        $classified = Classified::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'is_negotiable' => $data['is_negotiable'] ?? false,
            'condition' => $data['condition'] ?? null,
            'classified_category_id' => $data['classified_category_id'],
            'area_id' => $data['area_id'] ?? null,
            'seller_id' => auth()->id(),
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'expires_at' => now()->addDays(ClassifiedService::LIVE_DAYS),
            'contact_phone' => $data['contact_phone'] ?? null,
            'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
        ]);

        $this->saveImages($request, $classified);

        return redirect()->route('admin.classifieds.index', ['status' => 'active'])
            ->with('success', 'Item added and is now live in the marketplace.');
    }

    public function edit(Classified $classified)
    {
        $categories = ClassifiedCategory::active()->orderBy('sort_order')->get();
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        $classified->load('images');

        return view('admin.classifieds.edit', compact('classified', 'categories', 'areas'));
    }

    public function update(Request $request, Classified $classified)
    {
        $data = $this->validateData($request);
        $this->service->update($classified, $data);
        $this->saveImages($request, $classified);

        return redirect()->route('admin.classifieds.index', ['status' => $classified->status])
            ->with('success', 'Item updated.');
    }

    public function approve(Classified $classified)
    {
        $this->service->approve($classified, auth()->user());

        return redirect()->back()->with('success', 'Item approved and now live.');
    }

    public function reject(Request $request, Classified $classified)
    {
        $request->validate(['rejection_reason' => 'required|string|max:1000']);
        $this->service->reject($classified, $request->rejection_reason);

        return redirect()->back()->with('success', 'Item rejected with reason.');
    }

    public function toggleFeatured(Classified $classified)
    {
        $classified->update(['is_featured' => !$classified->is_featured]);

        return redirect()->back()->with('success', 'Featured status updated.');
    }

    public function destroy(Classified $classified)
    {
        $classified->delete();

        return redirect()->back()->with('success', 'Item deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'classified_category_id' => 'required|exists:classified_categories,id',
            'area_id' => 'nullable|exists:areas,id',
            'description' => 'nullable|string|max:5000',
            'price' => 'nullable|numeric|min:0|max:99999999',
            'is_negotiable' => 'nullable|boolean',
            'condition' => 'nullable|in:new,like_new,good,fair',
            'contact_phone' => 'nullable|string|max:20',
            'contact_whatsapp' => 'nullable|string|max:20',
            'images' => 'nullable|array|max:8',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
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

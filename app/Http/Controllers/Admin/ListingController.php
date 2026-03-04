<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function __construct(protected ListingService $listingService) {}

    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $query = Listing::with(['category', 'area', 'creator', 'images']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $listings = $query->latest()->paginate(20);

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
}

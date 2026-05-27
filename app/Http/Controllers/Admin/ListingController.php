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
}

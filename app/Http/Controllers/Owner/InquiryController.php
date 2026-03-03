<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $listingIds = auth()->user()->listings()->pluck('id');

        $query = Inquiry::whereIn('listing_id', $listingIds)
            ->with(['listing', 'user'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $inquiries = $query->paginate(15);

        $counts = [
            'all' => Inquiry::whereIn('listing_id', $listingIds)->count(),
            'new' => Inquiry::whereIn('listing_id', $listingIds)->where('status', 'new')->count(),
            'read' => Inquiry::whereIn('listing_id', $listingIds)->where('status', 'read')->count(),
            'replied' => Inquiry::whereIn('listing_id', $listingIds)->where('status', 'replied')->count(),
        ];

        return view('dashboard.inquiries.index', compact('inquiries', 'status', 'counts'));
    }

    public function show(Inquiry $inquiry)
    {
        // Ensure the owner can only see inquiries for their own listings
        $listingIds = auth()->user()->listings()->pluck('id');
        if (!$listingIds->contains($inquiry->listing_id)) {
            abort(403);
        }

        $inquiry->load(['listing', 'user']);

        // Mark as read if new
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }

        return view('dashboard.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        $listingIds = auth()->user()->listings()->pluck('id');
        if (!$listingIds->contains($inquiry->listing_id)) {
            abort(403);
        }

        $request->validate([
            'owner_reply' => 'required|string|min:5|max:5000',
        ]);

        $inquiry->update([
            'owner_reply' => $request->owner_reply,
            'status' => 'replied',
            'replied_at' => now(),
        ]);

        return redirect()->route('owner.inquiries.show', $inquiry)
            ->with('success', 'Reply sent successfully.');
    }
}

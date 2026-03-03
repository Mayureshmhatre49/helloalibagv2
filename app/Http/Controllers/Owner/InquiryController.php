<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Mail\InquiryReplyMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $listingIds = auth()->user()->listings()->pluck('id');
        if (!$listingIds->contains($inquiry->listing_id)) {
            abort(403);
        }

        $inquiry->load(['listing', 'user']);

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

        // Send reply email to the inquirer
        Mail::to($inquiry->email)->send(new InquiryReplyMail($inquiry));

        return redirect()->route('owner.inquiries.show', $inquiry)
            ->with('success', 'Reply sent successfully. An email has been sent to ' . $inquiry->name . '.');
    }
}

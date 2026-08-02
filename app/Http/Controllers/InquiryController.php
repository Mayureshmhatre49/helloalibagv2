<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:2000',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1|max:50',
        ]);

        $inquiry = Inquiry::create([
            'listing_id' => $listing->id,
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests' => $request->guests,
        ]);

        // Email the listing owner — never let a mail failure break the submission.
        if ($listing->creator && $listing->creator->email) {
            try {
                Mail::to($listing->creator->email)->send(new NewInquiryMail($inquiry));
            } catch (\Throwable $e) {
                Log::warning("New inquiry email to owner failed (inquiry #{$inquiry->id}): " . $e->getMessage());
            }
        } else {
            Log::warning("Inquiry #{$inquiry->id} on listing #{$listing->id} has no owner email to notify.");
        }

        // In-app notification for the owner
        if ($listing->created_by) {
            try {
                UserNotification::create([
                    'user_id' => $listing->created_by,
                    'type' => 'new_inquiry',
                    'title' => 'New Inquiry',
                    'message' => $request->name . ' sent an inquiry for ' . $listing->title,
                    'data' => ['inquiry_id' => $inquiry->id, 'listing_id' => $listing->id],
                    'action_url' => route('owner.inquiries.show', $inquiry),
                ]);
            } catch (\Throwable $e) {
                Log::warning("Inquiry notification failed (inquiry #{$inquiry->id}): " . $e->getMessage());
            }
        }

        // Send them to a dedicated confirmation page rather than back to the
        // form with a flash banner — after filling in a form people need an
        // unmistakable "it worked", and a small toast above a still-populated
        // form reads as if nothing happened.
        return redirect()
            ->route('listing.inquiry.thankyou', $listing)
            ->with('inquiry_sent', $inquiry->id);
    }

    /**
     * Confirmation page shown straight after an inquiry is sent. Guarded by the
     * flashed session key so the page can't be linked to or refreshed into —
     * without it we just send the visitor back to the listing.
     */
    public function thankYou(Request $request, Listing $listing)
    {
        if (! $request->session()->get('inquiry_sent')) {
            return redirect()->route('listing.show', [$listing->category->slug, $listing->slug]);
        }

        $listing->load(['category', 'area', 'images', 'creator']);

        return view('listing.inquiry-thank-you', compact('listing'));
    }
}

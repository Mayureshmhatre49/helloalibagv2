<?php

namespace App\Http\Controllers;

use App\Mail\NewInquiryMail;
use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\UserNotification;
use Illuminate\Http\Request;
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

        // Email the listing owner
        if ($listing->creator && $listing->creator->email) {
            Mail::to($listing->creator->email)->send(new NewInquiryMail($inquiry));
        }

        // In-app notification for the owner
        UserNotification::create([
            'user_id' => $listing->created_by,
            'type' => 'new_inquiry',
            'title' => 'New Inquiry',
            'message' => $request->name . ' sent an inquiry for ' . $listing->title,
            'data' => ['inquiry_id' => $inquiry->id, 'listing_id' => $listing->id],
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been sent successfully! The owner will get back to you soon.');
    }
}

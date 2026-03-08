<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReviewReceived;

class ReviewController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // Check if user already reviewed
        $existing = Review::where('listing_id', $listing->id)
                          ->where('user_id', $request->user()->id)
                          ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already reviewed this listing.');
        }

        // Enforce Verified Reviews: User must have either Inquired or Booked this listing
        $hasInquired = \App\Models\Inquiry::where('listing_id', $listing->id)
            ->where('user_id', $request->user()->id)
            ->exists();
            
        $hasBooked = \App\Models\Booking::where('listing_id', $listing->id)
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();

        if (!$hasInquired && !$hasBooked) {
            return redirect()->back()->with('error', 'You can only review listings you have inquired about or booked.');
        }

        $review = new Review();
        $review->listing_id = $listing->id;
        $review->user_id = $request->user()->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        // Assume pending to give admin control, or approved. Let's put pending for safety.
        $review->status = 'pending'; 
        $review->save();

        // Notify Listing Owner
        if ($listing->creator) {
            Mail::to($listing->creator->email)->send(new NewReviewReceived($listing, $review));
        }

        return redirect()->back()->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function helpful(Request $request, Review $review)
    {
        $key = 'helpful_review_' . $review->id;
        if ($request->session()->has($key)) {
            return response()->json(['message' => 'Already voted'], 422);
        }
        $review->increment('helpful_count');
        $request->session()->put($key, true);
        return response()->json(['helpful_count' => $review->helpful_count]);
    }
}

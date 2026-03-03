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
}

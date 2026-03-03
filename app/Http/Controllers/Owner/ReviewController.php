<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get reviews only for listings owned by this user
        $query = Review::with(['user', 'listing'])
            ->whereHas('listing', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });

        // Optional filtering by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Owners only see approved reviews (or we can show pending with a badge)
        // Let's show all so they know feedback is coming, but indicate status.
        $reviews = $query->latest()->paginate(15)->withQueryString();

        // Calculate average rating across all their listings
        $averageRating = Review::whereHas('listing', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->where('status', 'approved')->avg('rating') ?? 0;

        $totalReviews = Review::whereHas('listing', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })->where('status', 'approved')->count();

        return view('dashboard.reviews.index', compact('reviews', 'averageRating', 'totalReviews'));
    }
}

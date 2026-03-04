<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Listing;
use App\Models\User;

class ReviewService
{
    public function store(array $data, User $user): Review
    {
        return Review::create([
            'listing_id' => $data['listing_id'],
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function approve(Review $review): Review
    {
        $review->update(['status' => 'approved']);
        return $review;
    }

    public function reject(Review $review): Review
    {
        $review->update(['status' => 'rejected']);
        return $review;
    }

    public function getForListing(Listing $listing, int $perPage = 10)
    {
        return $listing->approvedReviews()
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function getPendingReviews(int $perPage = 20)
    {
        return Review::where('status', 'pending')
            ->with(['listing', 'user'])
            ->latest()
            ->paginate($perPage);
    }
}

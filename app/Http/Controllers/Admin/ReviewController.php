<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReviewApproved;
use App\Mail\ReviewRejected;
use App\Models\Review;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'listing']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('listing', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Show pending first, then newest
        $reviews = $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
                         ->latest()
                         ->paginate(20)
                         ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $applied = Review::where('id', $review->id)->where('status', '!=', 'approved')->update(['status' => 'approved']);
        $review = $review->fresh();

        if ($applied > 0) {
            $this->notify($review, 'approved');
        }

        return back()->with('success', $applied > 0 ? 'Review approved successfully.' : 'This review was already approved.');
    }

    public function reject(Review $review)
    {
        $applied = Review::where('id', $review->id)->where('status', '!=', 'rejected')->update(['status' => 'rejected']);
        $review = $review->fresh();

        if ($applied > 0) {
            $this->notify($review, 'rejected');
        }

        return back()->with('success', $applied > 0 ? 'Review rejected successfully.' : 'This review was already rejected.');
    }

    private function notify(Review $review, string $outcome): void
    {
        try {
            Mail::to($review->user->email)->send(
                $outcome === 'approved' ? new ReviewApproved($review) : new ReviewRejected($review)
            );
        } catch (\Throwable $e) {
            Log::warning("Review {$outcome} email failed: " . $e->getMessage());
        }

        try {
            UserNotification::create([
                'user_id' => $review->user_id,
                'type' => "review_{$outcome}",
                'title' => $outcome === 'approved' ? 'Review Approved' : 'Review Not Approved',
                'message' => $outcome === 'approved'
                    ? 'Your review of "' . $review->listing->title . '" is now live.'
                    : 'Your review of "' . $review->listing->title . '" was not approved.',
                'data' => ['review_id' => $review->id, 'listing_id' => $review->listing_id],
                'action_url' => $outcome === 'approved'
                    ? route('listing.show', [$review->listing->category->slug, $review->listing->slug]) . '#reviews'
                    : route('listing.show', [$review->listing->category->slug, $review->listing->slug]),
            ]);
        } catch (\Throwable $e) {
            Log::warning("Review {$outcome} notification failed: " . $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}

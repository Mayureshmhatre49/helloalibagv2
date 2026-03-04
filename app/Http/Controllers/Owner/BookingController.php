<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * List all bookings for the owner's listings
     */
    public function index(Request $request): View
    {
        $listingIds = $request->user()->listings()->pluck('id');

        $bookings = Booking::whereIn('listing_id', $listingIds)
            ->with(['listing', 'user'])
            ->latest()
            ->paginate(15);

        $stats = [
            'pending'   => Booking::whereIn('listing_id', $listingIds)->pending()->count(),
            'confirmed' => Booking::whereIn('listing_id', $listingIds)->confirmed()->count(),
            'total'     => Booking::whereIn('listing_id', $listingIds)->count(),
        ];

        return view('dashboard.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Show a single booking with full details
     */
    public function show(Booking $booking): View
    {
        $this->authorizeBooking($booking);
        $booking->load(['listing', 'user']);
        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * Confirm a pending booking
     */
    public function confirm(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBooking($booking);
        abort_if(!$booking->isPending(), 422, 'Only pending bookings can be confirmed.');

        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'owner_notes'  => $request->owner_notes,
        ]);

        UserNotification::create([
            'user_id' => $booking->user_id,
            'type'    => 'booking_confirmed',
            'title'   => 'Booking Confirmed! 🎉',
            'message' => "Your booking for {$booking->listing->title} has been confirmed!",
            'data'    => json_encode(['booking_id' => $booking->id]),
        ]);

        return back()->with('success', 'Booking confirmed and guest notified.');
    }

    /**
     * Decline a pending booking
     */
    public function decline(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBooking($booking);
        abort_if(!$booking->isPending(), 422, 'Only pending bookings can be declined.');

        $request->validate(['owner_notes' => 'nullable|string|max:500']);

        $booking->update([
            'status'      => 'declined',
            'owner_notes' => $request->owner_notes,
        ]);

        UserNotification::create([
            'user_id' => $booking->user_id,
            'type'    => 'booking_declined',
            'title'   => 'Booking Update',
            'message' => "Your booking request for {$booking->listing->title} could not be confirmed at this time.",
            'data'    => json_encode(['booking_id' => $booking->id]),
        ]);

        return back()->with('success', 'Booking declined and guest notified.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        $listingIds = auth()->user()->listings()->pluck('id');
        abort_if(!$listingIds->contains($booking->listing_id), 403);
    }
}

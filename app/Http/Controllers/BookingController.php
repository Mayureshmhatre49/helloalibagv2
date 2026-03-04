<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * User: show their booking request form (AJAX/inline on listing page — not a separate page)
     */
    public function store(Request $request, Listing $listing): RedirectResponse
    {
        $request->validate([
            'check_in'  => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'guests'    => 'required|integer|min:1|max:50',
            'message'   => 'required|string|min:10|max:1000',
        ]);

        $booking = Booking::create([
            'listing_id' => $listing->id,
            'user_id'    => $request->user()->id,
            'check_in'   => $request->check_in,
            'check_out'  => $request->check_out,
            'guests'     => $request->guests,
            'message'    => $request->message,
            'status'     => 'pending',
        ]);

        // Notify the listing owner
        \App\Models\UserNotification::create([
            'user_id' => $listing->created_by,
            'type'    => 'booking_request',
            'title'   => 'New Booking Request',
            'message' => "{$request->user()->name} sent a booking request for {$listing->title}.",
            'data'    => json_encode(['booking_id' => $booking->id, 'listing_id' => $listing->id]),
        ]);

        return back()->with('success', 'Booking request sent! The owner will confirm within 24 hours.');
    }

    /**
     * User: view their booking history
     */
    public function index(Request $request): View
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['listing.images'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * User: cancel a booking
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_if($booking->user_id !== $request->user()->id, 403);
        abort_if(!$booking->isPending(), 422, 'Only pending bookings can be cancelled.');

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}

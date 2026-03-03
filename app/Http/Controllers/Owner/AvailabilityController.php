<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\ListingAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Listing $listing)
    {
        $this->authorize('update', $listing);

        $availabilities = ListingAvailability::where('listing_id', $listing->id)
            ->where('date', '>=', now()->startOfMonth())
            ->orderBy('date')
            ->get()
            ->keyBy(fn($a) => $a->date->format('Y-m-d'));

        return view('dashboard.availability.index', compact('listing', 'availabilities'));
    }

    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $request->validate([
            'dates' => 'required|array',
            'dates.*.date' => 'required|date',
            'dates.*.status' => 'required|in:available,booked',
            'dates.*.price_override' => 'nullable|numeric|min:0',
            'dates.*.notes' => 'nullable|string|max:255',
        ]);

        foreach ($request->dates as $entry) {
            ListingAvailability::updateOrCreate(
                ['listing_id' => $listing->id, 'date' => $entry['date']],
                [
                    'status' => $entry['status'],
                    'price_override' => $entry['price_override'] ?? null,
                    'notes' => $entry['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('owner.availability.index', $listing)
            ->with('success', 'Availability updated!');
    }
}

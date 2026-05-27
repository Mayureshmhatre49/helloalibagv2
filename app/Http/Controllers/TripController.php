<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TripController extends Controller
{
    /**
     * GET /my-trips — list the current user's trips.
     */
    public function index()
    {
        $trips = Trip::ownedBy(auth()->id())
            ->withCount('listings')
            ->with(['listings' => fn ($q) => $q->limit(1)->with('images')])
            ->latest()
            ->paginate(12);

        return view('trips.index', compact('trips'));
    }

    /**
     * GET /my-trips/{trip:slug} — owner-only detail view (editable).
     */
    public function show(Trip $trip)
    {
        $this->authorizeOwner($trip);

        $trip->load([
            'listings' => fn ($q) => $q->with(['category:id,name,slug,icon', 'area:id,name,slug', 'images' => fn ($i) => $i->orderBy('sort_order')->limit(1)]),
        ]);

        return view('trips.show', compact('trip'));
    }

    /**
     * GET /trip/{token} — public read-only shareable version.
     */
    public function publicShow(string $token)
    {
        $trip = Trip::where('share_token', $token)->firstOrFail();

        $trip->load([
            'user:id,name,avatar',
            'listings' => fn ($q) => $q->with(['category:id,name,slug,icon', 'area:id,name,slug', 'images' => fn ($i) => $i->orderBy('sort_order')->limit(1)]),
        ]);

        return view('trips.public', compact('trip'));
    }

    /**
     * POST /my-trips — create a new trip.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'party_size' => 'nullable|integer|min:1|max:50',
        ]);

        $trip = auth()->user()->trips()->create(array_merge($data, [
            'party_size' => $data['party_size'] ?? 2,
        ]));

        return redirect()->route('trips.show', $trip)->with('success', 'Trip created — add some listings to get started.');
    }

    /**
     * PUT /my-trips/{trip} — update trip metadata.
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $this->authorizeOwner($trip);

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'party_size' => 'nullable|integer|min:1|max:50',
            'notes' => 'nullable|string|max:1000',
            'is_public' => 'nullable|boolean',
        ]);

        $trip->update(array_merge($data, [
            'is_public' => (bool) ($data['is_public'] ?? false),
            'party_size' => $data['party_size'] ?? $trip->party_size,
        ]));

        return redirect()->route('trips.show', $trip)->with('success', 'Trip updated.');
    }

    /**
     * DELETE /my-trips/{trip} — delete an entire trip.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        $this->authorizeOwner($trip);
        $name = $trip->name;
        $trip->delete();

        return redirect()->route('trips.index')->with('success', "Deleted trip “{$name}”.");
    }

    /**
     * POST /my-trips/add/{listing} — attach a listing to a trip.
     * Body: `trip_id` (existing) OR `new_trip_name` (creates a new trip).
     * Used by the "Add to trip" button on listing pages and search cards.
     *
     * Returns JSON when called via XHR/Fetch, otherwise redirects.
     */
    public function attachListing(Request $request, Listing $listing): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'trip_id' => 'nullable|integer|exists:trips,id',
            'new_trip_name' => 'nullable|string|max:120',
        ]);

        if (empty($data['trip_id']) && empty($data['new_trip_name'])) {
            return $this->respond($request, false, 'Pick an existing trip or name a new one.', 422);
        }

        $trip = !empty($data['trip_id'])
            ? Trip::ownedBy(auth()->id())->find($data['trip_id'])
            : auth()->user()->trips()->create(['name' => $data['new_trip_name']]);

        if (!$trip) {
            return $this->respond($request, false, 'Trip not found.', 404);
        }

        if ($trip->listings()->where('listings.id', $listing->id)->exists()) {
            return $this->respond($request, true, "Already in “{$trip->name}”.", 200, $trip);
        }

        $position = $trip->listings()->count();
        $trip->listings()->attach($listing->id, ['position' => $position]);

        return $this->respond($request, true, "Added to “{$trip->name}”.", 200, $trip);
    }

    /**
     * DELETE /my-trips/{trip}/listings/{listing} — remove a listing from a trip.
     */
    public function detachListing(Trip $trip, Listing $listing): RedirectResponse
    {
        $this->authorizeOwner($trip);
        $trip->listings()->detach($listing->id);

        return redirect()->route('trips.show', $trip)->with('success', 'Removed from trip.');
    }

    /**
     * POST /my-trips/{trip}/listings/{listing}/note — save a per-listing note.
     */
    public function updateListingNote(Request $request, Trip $trip, Listing $listing): RedirectResponse
    {
        $this->authorizeOwner($trip);
        $data = $request->validate(['note' => 'nullable|string|max:500']);

        $trip->listings()->updateExistingPivot($listing->id, ['note' => $data['note'] ?? null]);

        return redirect()->route('trips.show', $trip)->with('success', 'Note saved.');
    }

    /**
     * POST /my-trips/{trip}/listings/reorder — accepts an array of listing ids in new order.
     */
    public function reorderListings(Request $request, Trip $trip): JsonResponse
    {
        $this->authorizeOwner($trip);
        $data = $request->validate([
            'listing_ids' => 'required|array',
            'listing_ids.*' => 'integer',
        ]);

        foreach ($data['listing_ids'] as $pos => $listingId) {
            $trip->listings()->updateExistingPivot($listingId, ['position' => $pos]);
        }

        return response()->json(['ok' => true]);
    }

    // ── helpers ──

    private function authorizeOwner(Trip $trip): void
    {
        abort_unless($trip->user_id === auth()->id(), 403);
    }

    private function respond(Request $request, bool $ok, string $message, int $status = 200, ?Trip $trip = null): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'ok' => $ok,
                'message' => $message,
                'trip' => $trip ? ['id' => $trip->id, 'name' => $trip->name, 'slug' => $trip->slug, 'url' => route('trips.show', $trip)] : null,
            ], $status);
        }

        if (!$ok) {
            return redirect()->back()->with('error', $message);
        }

        return $trip
            ? redirect()->route('trips.show', $trip)->with('success', $message)
            : redirect()->back()->with('success', $message);
    }
}

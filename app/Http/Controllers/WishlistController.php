<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Listing;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with(['listing.category', 'listing.area', 'listing.images', 'listing.amenities'])
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request, Listing $listing)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('listing_id', $listing->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $isWishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'listing_id' => $listing->id,
            ]);
            $isWishlisted = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['wishlisted' => $isWishlisted]);
        }

        return redirect()->back()->with('success', $isWishlisted ? 'Added to wishlist!' : 'Removed from wishlist.');
    }
}

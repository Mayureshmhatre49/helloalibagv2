<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingImageController extends Controller
{
    public function destroy(ListingImage $image)
    {
        // Ensure user owns the listing
        if ($image->listing->created_by !== auth()->id()) {
            abort(403);
        }

        // Don't allow deleting the last image if minimum 1 is required
        if ($image->listing->images()->count() <= 1) {
            return back()->with('error', 'A listing must have at least one photo.');
        }

        // Delete from storage
        $path = str_replace('/storage/', '', $image->path);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        // If the deleted image was primary, make the first remaining image primary
        if ($image->is_primary) {
            $firstRemaining = $image->listing->images()->orderBy('sort_order')->first();
            if ($firstRemaining) {
                $firstRemaining->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Photo removed successfully.');
    }
}

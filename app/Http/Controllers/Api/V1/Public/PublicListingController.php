<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicListingResource;
use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Read-only, approved-only listings feed for trusted integration partners
 * (Sanctum token scoped to the `read:listings-public` ability). See
 * docs/public-api.md for the consumer-facing contract.
 */
class PublicListingController extends Controller
{
    protected function baseQuery()
    {
        return Listing::query()
            ->publiclyVisible()
            ->with(['category', 'area', 'images', 'amenities', 'tags', 'seoMeta']);
    }

    public function index(Request $request): JsonResponse
    {
        $query = $this->baseQuery();

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($areaSlug = $request->query('area')) {
            $query->whereHas('area', fn ($q) => $q->where('slug', $areaSlug));
        }

        if ($request->filled('updated_since')) {
            $updatedSince = $this->parseUpdatedSince($request->query('updated_since'));

            if ($updatedSince === null) {
                return response()->json([
                    'message' => 'updated_since must be a valid ISO 8601 timestamp.',
                ], 422);
            }

            $query->where('updated_at', '>=', $updatedSince);
        }

        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->has('is_premium')) {
            $query->where('is_premium', $request->boolean('is_premium'));
        }

        $perPage = min((int) $request->query('per_page', 15), 50);

        $listings = $query->paginate($perPage)->appends($request->query());

        return PublicListingResource::collection($listings)->response();
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $listing = $this->baseQuery()->where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Same response whether the slug doesn't exist, is pending/rejected,
            // or is an unpaid real-estate listing — never leak which case it is.
            return response()->json(['message' => 'Not found.'], 404);
        }

        return (new PublicListingResource($listing))->response();
    }

    /**
     * Returns null on a bad timestamp rather than throwing, so the caller
     * can respond with a plain JSON 422 regardless of the request's Accept
     * header (API consumers don't always send one).
     */
    protected function parseUpdatedSince(string $value): ?Carbon
    {
        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}

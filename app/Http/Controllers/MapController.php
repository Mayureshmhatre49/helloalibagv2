<?php

namespace App\Http\Controllers;

use App\Services\MapService;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    public function __construct(protected MapService $mapService) {}

    public function index()
    {
        $markers = $this->mapService->getApprovedListingMarkers();
        $categories = $this->mapService->getCategoryLegend();

        return view('map.index', compact('markers', 'categories'));
    }

    /**
     * Compact JSON feed used by the map page's JS and the search-page toggle.
     */
    public function markers(): JsonResponse
    {
        return response()->json([
            'markers' => $this->mapService->getApprovedListingMarkers(),
            'categories' => $this->mapService->getCategoryLegend(),
        ])->header('Cache-Control', 'public, max-age=300');
    }
}

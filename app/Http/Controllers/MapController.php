<?php

namespace App\Http\Controllers;

use App\Models\MapApiUsageLog;
use App\Services\MapApiService;
use App\Services\MapService;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    public function __construct(protected MapService $mapService, protected MapApiService $mapApi) {}

    public function index()
    {
        if (! $this->mapApi->isEnabled()) {
            return redirect()->route('home');
        }

        // One increment per page render — the Maps JavaScript API bills per
        // map initialization, and a render is exactly one initialization.
        $this->mapApi->recordHit(MapApiUsageLog::TYPE_MAP_LOAD);

        $markers = $this->mapService->getApprovedListingMarkers();
        $categories = $this->mapService->getCategoryLegend();

        return view('map.index', [
            'markers' => $markers,
            'categories' => $categories,
            'googleMapsKey' => $this->mapApi->apiKey(),
            'googleMapId' => $this->mapApi->mapId(),
        ]);
    }

    /**
     * Compact JSON feed used by the map page's JS and the search-page toggle.
     */
    public function markers(): JsonResponse
    {
        if (! $this->mapApi->isEnabled()) {
            return response()->json(['markers' => [], 'categories' => []], 404);
        }

        return response()->json([
            'markers' => $this->mapService->getApprovedListingMarkers(),
            'categories' => $this->mapService->getCategoryLegend(),
        ])->header('Cache-Control', 'public, max-age=300');
    }
}

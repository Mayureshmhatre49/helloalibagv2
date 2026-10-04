<?php

use App\Http\Controllers\Api\V1\Public\PublicListingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])->get('/user', function (Request $request) {
    return $request->user();
});

// Read-only feed for trusted integration partners (e.g. the Alibag Tourism
// site pulling and caching approved listings on a schedule). See
// docs/public-api.md for the consumer-facing contract.
Route::prefix('v1/public')
    ->middleware([
        'json.api',
        'secure.api',
        'auth:sanctum',
        'abilities:read:listings-public',
        'throttle:public-api',
        'log.api',
    ])
    ->group(function () {
        Route::get('listings', [PublicListingController::class, 'index']);
        Route::get('listings/{slug}', [PublicListingController::class, 'show']);
    });

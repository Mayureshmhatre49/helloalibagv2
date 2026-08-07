<?php

namespace App\Http\Controllers;

use App\Services\LandingPageService;

class LandingController extends Controller
{
    public function __construct(protected LandingPageService $service) {}

    public function show(string $slug)
    {
        $ctx = $this->service->resolve($slug);
        abort_unless($ctx, 404);

        $listings = $this->service->listings($ctx);
        $copy = $this->service->copy($ctx, $listings->total());
        $faqs = $this->service->faqs($ctx, $listings);
        $related = $this->service->relatedLinks($ctx);

        $robots = $listings->total() > 0 ? 'index, follow' : 'noindex, follow';

        return view('landing.show', compact('ctx', 'listings', 'copy', 'faqs', 'related', 'robots'));
    }
}

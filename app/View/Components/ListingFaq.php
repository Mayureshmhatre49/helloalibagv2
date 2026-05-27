<?php

namespace App\View\Components;

use App\Models\Listing;
use App\Services\ListingFaqService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListingFaq extends Component
{
    public array $faqs;

    public function __construct(public Listing $listing, ListingFaqService $faqService)
    {
        $this->faqs = $faqService->forListing($listing);
    }

    public function render(): View|Closure|string
    {
        return view('components.listing-faq');
    }
}

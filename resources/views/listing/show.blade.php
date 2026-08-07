@extends('layouts.app')
{{-- Admin-entered SEO (seoMeta) overrides the auto-generated defaults. --}}
@section('title', $listing->seoMeta?->meta_title ?: ($listing->title . ' — ' . $listing->category->name . ' in Alibaug'))
@section('meta_description', $listing->seoMeta?->meta_description ?: addslashes(Str::limit(strip_tags($listing->description ?: ($listing->title . ' — ' . $listing->category->name . ' in ' . ($listing->area?->name ?? 'Alibaug') . ', Maharashtra. View photos, price, location and contact details on Hello Alibaug.')), 160)))
@section('og_image', $listing->seoMeta?->og_image ?: ($listing->getPrimaryImageUrl() ?: asset('images/og-default.jpg')))
@section('canonical', $listing->seoMeta?->canonical_url ?: url()->current())
@section('og_type', 'website')
@section('robots', ($isPreview ?? false) ? 'noindex, nofollow' : 'index, follow')
@if($listing->seoMeta?->meta_keywords)
@section('keywords', $listing->seoMeta->meta_keywords)
@endif

@section('jsonld')
@if($listing->category->slug === 'stay')
    @include('partials.schema.lodging', ['listing' => $listing])
@elseif($listing->category->slug === 'eat')
    @include('partials.schema.restaurant', ['listing' => $listing])
@endif

@if($listing->listingFaqs && $listing->listingFaqs->count() > 0)
    @include('partials.schema.faq', ['faqs' => $listing->listingFaqs])
@endif

@include('partials.schema.breadcrumbs', ['crumbs' => [
    ['label' => 'Home', 'url' => route('home')],
    ['label' => $listing->category->name, 'url' => route('category.show', $listing->category)],
    ['label' => $listing->title, 'url' => route('listing.show', [$listing->category->slug, $listing->slug])],
]])
@endsection

@section('content')
@if($isPreview ?? false)
    <div class="bg-amber-500 text-white">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center gap-x-3 gap-y-1 justify-center text-center text-sm">
            <span class="material-symbols-outlined text-[20px]">visibility</span>
            <span class="font-bold">Preview mode</span>
            <span class="opacity-90">This listing is <strong>{{ ucfirst($listing->status) }}</strong> and not visible to the public yet.</span>
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.listings.index', ['status' => $listing->status]) }}" class="underline font-semibold hover:opacity-80">Back to review queue</a>
            @endif
        </div>
    </div>
@endif
@php
    $avgRating   = $listing->getAverageRating();
    $reviewCount = $listing->approvedReviews->count();
    $dynAttrs    = $listing->getDynamicAttributes();
    $catSlug     = $listing->category->slug ?? '';
    $priceLabel  = match($catSlug) {
        'stay'       => '/ night',
        'eat'        => 'for 2',
        'events'     => 'onwards',
        'explore'    => '/ person',
        'services'   => 'onwards',
        'real-estate'=> '',
        default      => '',
    };
    $template = 'listing.categories.' . $catSlug;
    if (!view()->exists($template)) {
        $template = 'listing.categories.stay'; // sensible fallback
    }
@endphp

@include($template)

@endsection

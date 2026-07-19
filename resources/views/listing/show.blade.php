@extends('layouts.app')
@section('title', $listing->title . ' — ' . $listing->category->name . ' in Alibaug')
@section('meta_description', addslashes(Str::limit(strip_tags($listing->description ?: ($listing->title . ' — ' . $listing->category->name . ' in ' . ($listing->area?->name ?? 'Alibaug') . ', Maharashtra. View photos, price, location and contact details on Hello Alibaug.')), 160)))
@section('og_image', $listing->getPrimaryImageUrl() ?: asset('images/og-default.jpg'))
@section('og_type', 'website')
@section('robots', ($isPreview ?? false) ? 'noindex, nofollow' : 'index, follow')

@section('jsonld')
@php
    $ldRating = $listing->getAverageRating();
    $ldReviews = $listing->approvedReviews->count();
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "{{ $listing->category->slug === 'stay' ? 'LodgingBusiness' : ($listing->category->slug === 'eat' ? 'Restaurant' : 'LocalBusiness') }}",
  "name": "{{ addslashes($listing->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($listing->description ?? ''), 150)) }}",
  "image": "{{ $listing->getPrimaryImageUrl() ?: asset('images/og-default.jpg') }}",
  "url": "{{ url()->current() }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($listing->address ?? '') }}",
    "addressLocality": "{{ $listing->area?->name ?? 'Alibaug' }}",
    "addressRegion": "Maharashtra",
    "addressCountry": "IN"
  }
  @if($listing->latitude && $listing->longitude)
  ,"geo": { "@type": "GeoCoordinates", "latitude": {{ $listing->latitude }}, "longitude": {{ $listing->longitude }} }
  @endif
  @if($listing->phone)
  ,"telephone": "{{ $listing->phone }}"
  @endif
  @if($listing->price)
  ,"priceRange": "₹{{ number_format($listing->price) }}"
  @endif
  @if($ldReviews > 0)
  ,"aggregateRating": { "@type": "AggregateRating", "ratingValue": "{{ number_format($ldRating, 1) }}", "reviewCount": {{ $ldReviews }} }
  @endif
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"
  },{
    "@type": "ListItem", "position": 2, "name": "{{ $listing->category->name }}", "item": "{{ route('category.show', $listing->category) }}"
  },{
    "@type": "ListItem", "position": 3, "name": "{{ addslashes($listing->title) }}"
  }]
}
</script>
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

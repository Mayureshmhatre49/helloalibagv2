@extends('layouts.app')
@section('title', $listing->title . ' — ' . $listing->category->name . ' in Alibaug')

@section('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "{{ $listing->category->slug === 'stay' ? 'LodgingBusiness' : ($listing->category->slug === 'eat' ? 'Restaurant' : 'LocalBusiness') }}",
  "name": "{{ addslashes($listing->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($listing->description ?? ''), 150)) }}",
  "image": "{{ $listing->getPrimaryImageUrl() ?: asset('images/og-default.jpg') }}",
  "url": "{{ request()->url() }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($listing->address ?? '') }}",
    "addressLocality": "{{ $listing->area?->name ?? 'Alibaug' }}",
    "addressRegion": "Maharashtra",
    "addressCountry": "IN"
  }
  @if($listing->phone)
  ,"telephone": "{{ $listing->phone }}"
  @endif
  @if($listing->price)
  ,"priceRange": "₹{{ number_format($listing->price) }}"
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

{{-- resources/views/partials/schema/restaurant.blade.php --}}
@php
    $cuisine = $listing->listingAttributes->where('attribute_key', 'cuisine')->first()?->attribute_value;
    $cuisines = $cuisine ? array_map('trim', explode(',', $cuisine)) : ['Konkani', 'Seafood'];
    $primaryImage = $listing->getPrimaryImageUrl();
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Restaurant",
  "name": "{{ addslashes($listing->title) }}",
  "url": "{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}",
  "image": "{{ $primaryImage ?: asset('images/og-default.jpg') }}",
  "servesCuisine": {!! json_encode($cuisines, JSON_UNESCAPED_SLASHES) !!},
  "priceRange": "₹{{ number_format($listing->price) }} for two",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($listing->address ?? '') }}",
    "addressLocality": "{{ addslashes($listing->area->name ?? 'Alibaug') }}",
    "addressRegion": "Maharashtra",
    "addressCountry": "IN"
  },
  @if($listing->latitude && $listing->longitude)
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": {{ $listing->latitude }},
    "longitude": {{ $listing->longitude }}
  },
  @endif
  "acceptsReservations": "True"
}
</script>

{{-- resources/views/partials/schema/lodging.blade.php --}}
@php
    $bedrooms = $listing->listingAttributes->where('attribute_key', 'bedrooms')->first()?->attribute_value;
    $images = $listing->images->pluck('url')->toArray();
    $primaryImage = $listing->getPrimaryImageUrl();
    if ($primaryImage && !in_array($primaryImage, $images)) {
        array_unshift($images, $primaryImage);
    }
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LodgingBusiness",
  "@id": "{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}#lodging",
  "name": "{{ addslashes($listing->title) }}",
  "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($listing->description), 200)) }}",
  "url": "{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}",
  "image": {!! json_encode($images, JSON_UNESCAPED_SLASHES) !!},
  "telephone": "{{ $listing->phone ?? '+91-9209479178' }}",
  "priceRange": "₹₹₹₹",
  "currenciesAccepted": "INR",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ addslashes($listing->address ?? '') }}",
    "addressLocality": "{{ addslashes($listing->area->name ?? 'Alibaug') }}",
    "addressRegion": "Maharashtra",
    "postalCode": "402201",
    "addressCountry": "IN"
  },
  @if($listing->latitude && $listing->longitude)
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": {{ $listing->latitude }},
    "longitude": {{ $listing->longitude }}
  },
  @endif
  @if($bedrooms)
  "numberOfRooms": {{ (int) $bedrooms }},
  @endif
  "makesOffer": {
    "@type": "Offer",
    "priceSpecification": {
      "@type": "UnitPriceSpecification",
      "price": {{ (int) $listing->price }},
      "priceCurrency": "INR",
      "unitCode": "DAY",
      "referenceQuantity": { "@type": "QuantitativeValue", "value": 1, "unitCode": "DAY" }
    },
    "availability": "https://schema.org/InStock"
  },
  "isPartOf": { "@id": "https://helloalibaug.com/#organization" }
}
</script>

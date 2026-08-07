{{-- resources/views/partials/schema/itemlist.blade.php --}}
@if(isset($beaches) && count($beaches) > 0)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'Beaches in and around Alibaug',
    'numberOfItems' => count($beaches),
    'itemListElement' => collect($beaches)->values()->map(fn ($b, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'item' => [
            '@type' => 'Beach',
            'name' => $b['name'] ?? '',
            'description' => $b['description'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Alibaug',
                'addressRegion' => 'Maharashtra',
                'addressCountry' => 'IN',
            ],
        ],
    ])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

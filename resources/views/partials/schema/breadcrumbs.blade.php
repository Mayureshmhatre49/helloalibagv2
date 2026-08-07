{{-- resources/views/partials/schema/breadcrumbs.blade.php --}}
@if(isset($crumbs) && count($crumbs) > 0)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['label'] ?? '',
        'item' => $c['url'] ?? '',
    ])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

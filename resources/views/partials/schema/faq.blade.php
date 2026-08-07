{{-- resources/views/partials/schema/faq.blade.php --}}
@if(isset($faqs) && count($faqs) > 0)
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
        '@type' => 'Question',
        'name' => is_array($f) ? ($f['question'] ?? $f['q'] ?? '') : ($f->question ?? ''),
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => strip_tags(is_array($f) ? ($f['answer'] ?? $f['a'] ?? '') : ($f->answer ?? '')),
        ],
    ])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

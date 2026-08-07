{{-- resources/views/partials/schema/article.blade.php --}}
@php
    $words = str_word_count(strip_tags($post->content ?? ''));
    $authorSlug = \Illuminate\Support\Str::slug($post->author->name ?? 'ankit-deshmukh');
@endphp
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ addslashes($post->title) }}",
  "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($post->meta_description ?: ($post->excerpt ?: $post->content)), 200)) }}",
  "image": "{{ $ogImageUrl ?? $post->getFeaturedImageUrl() }}",
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at ? $post->updated_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "@id": "https://helloalibaug.com/authors/{{ $authorSlug }}#person",
    "name": "{{ addslashes($post->author->name ?? 'Ankit Deshmukh') }}",
    "url": "https://helloalibaug.com/authors/{{ $authorSlug }}"
  },
  "publisher": { "@id": "https://helloalibaug.com/#organization" },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('blog.show', $post->slug) }}"
  },
  @if($post->category)
  "articleSection": "{{ addslashes($post->category->name) }}",
  @endif
  "wordCount": {{ $words }},
  "inLanguage": "en-IN"
}
</script>

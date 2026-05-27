@extends('layouts.app')

@section('title', $copy['metaTitle'])
@section('meta_description', $copy['metaDescription'])

@push('styles')
{{-- CollectionPage + ItemList schema for the landing page. --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": @json($copy['h1']),
    "description": @json($copy['metaDescription']),
    "url": "{{ url('/' . $ctx['slug']) }}",
    "isPartOf": { "@type": "WebSite", "name": "Hello Alibaug", "url": "{{ url('/') }}" }
    @if ($listings->total() > 0)
    , "mainEntity": {
        "@type": "ItemList",
        "numberOfItems": {{ $listings->total() }},
        "itemListElement": [
            @foreach ($listings as $i => $l)
            {
                "@type": "ListItem",
                "position": {{ $i + 1 }},
                "url": @json(route('listing.show', [$l->category->slug, $l->slug])),
                "name": @json($l->title)
            }@if (!$loop->last),@endif
            @endforeach
        ]
    }
    @endif
}
</script>
{{-- FAQPage schema. --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        @foreach ($faqs as $faq)
        {
            "@type": "Question",
            "name": @json($faq['q']),
            "acceptedAnswer": { "@type": "Answer", "text": @json(strip_tags($faq['a'])) }
        }@if (!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endpush

@section('content')
{{-- ── HERO ────────────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
        <nav class="flex items-center gap-2 text-text-secondary text-sm font-medium mb-5" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <a href="{{ route('category.show', $ctx['category']) }}" class="hover:text-primary transition-colors">{{ $ctx['category']->name }}</a>
            @if ($ctx['area'])
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="{{ route('area.show', $ctx['area']) }}" class="hover:text-primary transition-colors">{{ $ctx['area']->name }}</a>
            @endif
        </nav>

        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                <span class="material-symbols-outlined text-[16px]">{{ $ctx['category']->icon ?? 'place' }}</span>
                @if ($ctx['area'])
                    {{ $ctx['category']->name }} · {{ $ctx['area']->name }}
                @else
                    {{ $ctx['category']->name }} · Alibaug
                @endif
            </div>

            <h1 class="text-slate-900 font-serif font-bold text-4xl sm:text-5xl lg:text-6xl tracking-tight leading-tight mb-4">
                {{ $copy['h1'] }}
            </h1>

            <p class="text-slate-600 text-lg leading-relaxed">{{ $copy['intro'] }}</p>

            <div class="flex flex-wrap items-center gap-3 mt-6">
                <a href="{{ route('map.index') }}" class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-primary/40 text-slate-700 font-bold text-sm px-4 py-2 rounded-xl transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">map</span> View on map
                </a>
                @if ($ctx['area'])
                    <a href="{{ route('area.show', $ctx['area']) }}" class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-primary/40 text-slate-700 font-bold text-sm px-4 py-2 rounded-xl transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">explore</span> About {{ $ctx['area']->name }}
                    </a>
                @endif
                <x-share-menu :text="$copy['h1'] . ' — Hello Alibaug'" label="Share" />
            </div>
        </div>
    </div>
</section>

{{-- ── LISTINGS GRID ───────────────────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
    @if ($listings->isEmpty())
        <div class="bg-white border border-border-light rounded-2xl p-12 text-center">
            <span class="material-symbols-outlined text-slate-300 text-[48px] mb-3">search_off</span>
            <h2 class="text-slate-900 font-bold text-xl mb-2">No matching listings yet</h2>
            <p class="text-text-secondary text-sm max-w-md mx-auto mb-6">We're actively curating {{ strtolower($ctx['noun']) }}{{ $ctx['area'] ? ' in ' . $ctx['area']->name : '' }}. Try a nearby area or browse all listings.</p>
            <a href="{{ route('search', ['category_id' => $ctx['category']->id]) }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                Browse all {{ $ctx['category']->name }} <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @else
        <div class="flex items-baseline justify-between mb-6 flex-wrap gap-3">
            <p class="text-text-secondary text-sm">
                Showing <strong class="text-slate-900">{{ $listings->count() }}</strong> of <strong class="text-slate-900">{{ $listings->total() }}</strong> {{ strtolower($ctx['noun']) }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($listings as $listing)
                <x-listing-card :listing="$listing" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $listings->links() }}
        </div>
    @endif
</section>

{{-- ── FAQ ─────────────────────────────────────────────────────────────── --}}
@if (count($faqs) > 0)
    <section class="bg-white border-y border-border-light">
        <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2 text-center">Frequently asked</p>
            <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-8 text-center">About {{ strtolower($copy['h1']) }}</h2>

            <div class="space-y-2.5" x-data="{ open: 0 }">
                @foreach ($faqs as $i => $faq)
                    <div class="bg-slate-50 border border-slate-100 rounded-xl overflow-hidden hover:border-slate-200 transition-colors">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}" type="button"
                                class="w-full text-left px-5 py-4 flex items-center justify-between gap-4 hover:bg-slate-100/60 transition-colors"
                                :aria-expanded="open === {{ $i }} ? 'true' : 'false'">
                            <span class="text-slate-900 font-semibold text-base pr-4">{{ $faq['q'] }}</span>
                            <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 flex-shrink-0"
                                  :class="open === {{ $i }} ? 'rotate-180 text-primary' : ''">expand_more</span>
                        </button>
                        <div x-show="open === {{ $i }}"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;"
                             class="px-5 pb-4 text-slate-700 text-sm leading-relaxed">
                            {!! $faq['a'] !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ── RELATED LINKS ───────────────────────────────────────────────────── --}}
@if (count($related) > 0)
    <section class="bg-slate-50">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2 text-center">Keep exploring</p>
            <h2 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl tracking-tight mb-8 text-center">You might also like</h2>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ($related as $link)
                    <a href="{{ $link['url'] }}" class="group bg-white border border-border-light hover:border-primary/40 hover:shadow-md rounded-xl px-4 py-3 flex items-center justify-between transition-all">
                        <span class="text-slate-900 font-semibold text-sm pr-2">{{ $link['label'] }}</span>
                        <span class="material-symbols-outlined text-slate-400 group-hover:text-primary group-hover:translate-x-0.5 transition-all text-[18px]">arrow_forward</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ── NEWSLETTER ──────────────────────────────────────────────────────── --}}
<div class="max-w-[1200px] mx-auto px-4">
    <x-newsletter-cta />
</div>
@endsection

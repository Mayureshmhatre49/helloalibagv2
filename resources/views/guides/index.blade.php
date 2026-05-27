@extends('layouts.app')

@section('title', 'Alibaug Travel Guides — Curated Picks for Stays, Beaches & Experiences')
@section('meta_description', 'Editorial guides to Alibaug: the best beaches, family-friendly villas, monsoon picks, foodie trails, and weekend itineraries. Handpicked by locals.')

@push('styles')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Alibaug Travel Guides",
    "description": "Curated, editorial guides to stays, dining, and experiences in Alibaug.",
    "url": "{{ route('guides.index') }}"
}
</script>
@endpush

@section('content')
{{-- ── HERO ────────────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-10">
        <nav class="flex items-center gap-2 text-text-secondary text-sm font-medium mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-slate-700">Guides</span>
        </nav>

        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                <span class="material-symbols-outlined text-[16px]">menu_book</span>
                Editorial Guides
            </div>
            <h1 class="text-slate-900 font-serif font-bold text-4xl sm:text-5xl md:text-6xl tracking-tight leading-tight mb-4">
                The Alibaug <span class="text-primary">Travel Guides</span>
            </h1>
            <p class="text-slate-600 text-lg sm:text-xl leading-relaxed">
                Handpicked itineraries, neighborhood deep-dives, and seasonal recommendations — written by people who actually live and travel here.
            </p>
        </div>
    </div>
</section>

{{-- ── FEATURED ────────────────────────────────────────────────────────── --}}
@if ($featured->isNotEmpty())
    <section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2">Editor's Picks</p>
                <h2 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl tracking-tight">This week's featured guides</h2>
            </div>
        </div>

        <div class="grid lg:grid-cols-[2fr_1fr] gap-5 lg:gap-6">
            {{-- Hero featured (first one) --}}
            @php $hero = $featured->first(); @endphp
            <a href="{{ route('guides.show', $hero) }}" class="group block bg-white rounded-2xl border border-border-light overflow-hidden hover:shadow-xl transition-all">
                <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative">
                    @if ($hero->heroImageUrl())
                        <img src="{{ $hero->heroImageUrl() }}" alt="{{ $hero->hero_image_alt ?? $hero->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                            <span class="material-symbols-outlined" style="font-size:48px">menu_book</span>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4 bg-amber-400 text-amber-950 text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">star</span>
                        Featured
                    </div>
                </div>
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-3 text-xs font-semibold text-text-secondary">
                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                        <span>{{ $hero->reading_time }} min read</span>
                        @if ($hero->published_at)
                            <span class="text-slate-300">·</span>
                            <span>{{ $hero->published_at->format('M j, Y') }}</span>
                        @endif
                    </div>
                    <h3 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl leading-tight mb-3 group-hover:text-primary transition-colors">{{ $hero->title }}</h3>
                    @if ($hero->intro)
                        <p class="text-slate-600 text-base leading-relaxed mb-4 line-clamp-3">{{ $hero->intro }}</p>
                    @endif
                    <span class="inline-flex items-center gap-1.5 text-primary font-bold text-sm group-hover:gap-2.5 transition-all">
                        Read guide <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </span>
                </div>
            </a>

            {{-- Secondary featured (2 stacked) --}}
            <div class="grid grid-cols-1 gap-5">
                @foreach ($featured->skip(1) as $g)
                    <a href="{{ route('guides.show', $g) }}" class="group block bg-white rounded-2xl border border-border-light overflow-hidden hover:shadow-lg transition-all">
                        <div class="aspect-[16/9] bg-slate-100 overflow-hidden">
                            @if ($g->heroImageUrl())
                                <img src="{{ $g->heroImageUrl() }}" alt="{{ $g->hero_image_alt ?? $g->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                    <span class="material-symbols-outlined" style="font-size:32px">menu_book</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2 text-xs font-semibold text-text-secondary">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span>{{ $g->reading_time }} min read</span>
                            </div>
                            <h3 class="text-slate-900 font-serif font-bold text-lg leading-tight mb-1 group-hover:text-primary transition-colors line-clamp-2">{{ $g->title }}</h3>
                            @if ($g->intro)
                                <p class="text-slate-600 text-sm leading-snug line-clamp-2">{{ $g->intro }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ── ALL GUIDES GRID ─────────────────────────────────────────────────── --}}
<section class="bg-slate-50 border-y border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="flex items-end justify-between mb-6">
            <div>
                <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2">Browse All</p>
                <h2 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl tracking-tight">Every guide</h2>
            </div>
            <p class="text-text-secondary text-sm hidden sm:block">{{ $guides->total() }} {{ Str::plural('guide', $guides->total()) }}</p>
        </div>

        @if ($guides->isEmpty())
            <div class="bg-white rounded-2xl border border-border-light p-12 text-center">
                <span class="material-symbols-outlined text-slate-300 text-[48px] mb-3">menu_book</span>
                <h3 class="text-slate-900 font-bold text-lg mb-2">More guides coming soon</h3>
                <p class="text-text-secondary text-sm max-w-md mx-auto">We're working on a fresh round of editorial picks. Check back in a few days, or sign up for our newsletter to be the first to know.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($guides as $g)
                    <a href="{{ route('guides.show', $g) }}" class="group block bg-white rounded-2xl border border-border-light overflow-hidden hover:shadow-lg transition-all">
                        <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                            @if ($g->heroImageUrl())
                                <img src="{{ $g->heroImageUrl() }}" alt="{{ $g->hero_image_alt ?? $g->title }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                    <span class="material-symbols-outlined" style="font-size:32px">menu_book</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2 text-xs font-semibold text-text-secondary">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span>{{ $g->reading_time }} min read</span>
                                @if ($g->published_at)
                                    <span class="text-slate-300">·</span>
                                    <span>{{ $g->published_at->format('M j') }}</span>
                                @endif
                            </div>
                            <h3 class="text-slate-900 font-serif font-bold text-lg leading-tight mb-1.5 group-hover:text-primary transition-colors line-clamp-2">{{ $g->title }}</h3>
                            @if ($g->intro)
                                <p class="text-slate-600 text-sm leading-snug line-clamp-2">{{ $g->intro }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $guides->links() }}
            </div>
        @endif
    </div>
</section>

{{-- ── NEWSLETTER ──────────────────────────────────────────────────────── --}}
<div class="max-w-[1200px] mx-auto px-4">
    <x-newsletter-cta />
</div>
@endsection

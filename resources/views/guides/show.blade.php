@extends('layouts.app')

@section('title', $guide->meta_title ?: $guide->title)
@section('meta_description', $guide->meta_description ?: $guide->intro)

@push('styles')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": @json($guide->title),
    "description": @json($guide->intro ?: $guide->meta_description ?: ''),
    "image": @json($guide->heroImageUrl() ?: asset('images/og-default.jpg')),
    "datePublished": @json(optional($guide->published_at)->toIso8601String()),
    "dateModified": @json($guide->updated_at->toIso8601String()),
    @if ($guide->author)
    "author": { "@type": "Person", "name": @json($guide->author->name) },
    @endif
    "publisher": {
        "@type": "Organization",
        "name": "Hello Alibaug",
        "logo": { "@type": "ImageObject", "url": "{{ asset('images/logo.png') }}" }
    },
    "mainEntityOfPage": "{{ route('guides.show', $guide) }}"
}
</script>
<style>
    .guide-content { color: #1f2937; line-height: 1.75; font-size: 17px; }
    .guide-content h2 { font-family: 'Playfair Display', serif; font-size: 1.875rem; font-weight: 700; color: #0d161b; margin: 2.5rem 0 1rem; line-height: 1.25; scroll-margin-top: 100px; }
    .guide-content h3 { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #0d161b; margin: 1.75rem 0 0.75rem; line-height: 1.3; }
    .guide-content p { margin-bottom: 1.25rem; }
    .guide-content a { color: #1183d4; text-decoration: underline; text-decoration-thickness: 1.5px; text-underline-offset: 2px; }
    .guide-content a:hover { color: #0c6ab0; }
    .guide-content ul, .guide-content ol { margin: 1rem 0 1.25rem; padding-left: 1.5rem; }
    .guide-content li { margin-bottom: 0.5rem; }
    .guide-content ul li { list-style: disc; }
    .guide-content ol li { list-style: decimal; }
    .guide-content blockquote { border-left: 4px solid #e8a020; background: #fefce8; padding: 1rem 1.25rem; margin: 1.5rem 0; border-radius: 0 0.75rem 0.75rem 0; font-style: italic; color: #4c799a; }
    .guide-content img { border-radius: 1rem; margin: 1.5rem 0; max-width: 100%; height: auto; }
    .guide-content strong { color: #0d161b; font-weight: 700; }
</style>
@endpush

@section('content')
@php
    // Extract H2 headings from content for the floating TOC. Cheap regex pass; no DOM lib needed.
    $tocItems = [];
    if (!empty($guide->content)) {
        preg_match_all('/<h2[^>]*>(.+?)<\/h2>/i', $guide->content, $matches);
        foreach ($matches[1] ?? [] as $heading) {
            $text = trim(strip_tags($heading));
            $tocItems[] = [
                'id' => 'h-' . Str::slug($text),
                'text' => $text,
            ];
        }
        // Inject ids into the H2 tags so anchor links work
        $tocContent = preg_replace_callback('/<h2([^>]*)>(.+?)<\/h2>/i', function ($m) {
            $text = trim(strip_tags($m[2]));
            $id = 'h-' . Str::slug($text);
            return "<h2 id=\"{$id}\"{$m[1]}>{$m[2]}</h2>";
        }, $guide->content);
    } else {
        $tocContent = '';
    }
@endphp

{{-- ── HERO ────────────────────────────────────────────────────────────── --}}
<article>
    <header class="relative bg-gradient-to-br from-slate-50 via-white to-slate-50 border-b border-border-light">
        <div class="max-w-[920px] mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
            <nav class="flex items-center gap-2 text-text-secondary text-sm font-medium mb-6" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <a href="{{ route('guides.index') }}" class="hover:text-primary transition-colors">Guides</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-slate-700 truncate">{{ Str::limit($guide->title, 60) }}</span>
            </nav>

            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                <span class="material-symbols-outlined text-[16px]">menu_book</span>
                Guide
            </div>

            <h1 class="text-slate-900 font-serif font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight mb-5">
                {{ $guide->title }}
            </h1>

            @if ($guide->intro)
                <p class="text-slate-600 text-xl leading-relaxed mb-6 max-w-3xl">{{ $guide->intro }}</p>
            @endif

            <div class="flex flex-wrap items-center gap-5 text-sm text-text-secondary font-medium border-t border-border-light pt-5">
                @if ($guide->author)
                    <div class="flex items-center gap-2.5">
                        <img src="{{ $guide->author->getAvatarUrl() }}" alt="{{ $guide->author->name }}"
                             class="w-9 h-9 rounded-full object-cover border-2 border-white shadow">
                        <div>
                            <p class="text-slate-900 font-bold text-sm leading-none">{{ $guide->author->name }}</p>
                            <p class="text-text-secondary text-xs mt-0.5">Author</p>
                        </div>
                    </div>
                    <span class="text-slate-300 hidden sm:inline">·</span>
                @endif

                <span class="inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                    {{ $guide->reading_time }} min read
                </span>

                @if ($guide->published_at)
                    <span class="text-slate-300 hidden sm:inline">·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ $guide->published_at->format('M j, Y') }}
                    </span>
                @endif

                <span class="ml-auto">
                    <x-share-menu :text="$guide->title . ' — Hello Alibaug'" label="Share" />
                </span>
            </div>
        </div>

        @if ($guide->heroImageUrl())
            <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                <div class="aspect-[16/9] bg-slate-100 rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ $guide->heroImageUrl() }}" alt="{{ $guide->hero_image_alt ?? $guide->title }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        @endif
    </header>

    {{-- ── BODY + SIDEBAR ──────────────────────────────────────────────── --}}
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid lg:grid-cols-[1fr_300px] gap-10 lg:gap-14">

            {{-- Main content --}}
            <div class="min-w-0">
                {{-- TOC (mobile inline / desktop hidden — desktop uses the sidebar version) --}}
                @if (count($tocItems) > 1)
                    <details class="lg:hidden mb-8 bg-slate-50 border border-border-light rounded-2xl p-5">
                        <summary class="flex items-center justify-between cursor-pointer">
                            <span class="text-slate-900 font-bold text-sm uppercase tracking-wider flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-primary">format_list_numbered</span>
                                In this guide
                            </span>
                            <span class="material-symbols-outlined text-slate-400">expand_more</span>
                        </summary>
                        <ol class="mt-4 space-y-2 text-sm">
                            @foreach ($tocItems as $i => $item)
                                <li>
                                    <a href="#{{ $item['id'] }}" class="text-slate-700 hover:text-primary transition-colors flex items-baseline gap-2">
                                        <span class="text-text-secondary font-semibold text-xs tabular-nums w-5">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="font-medium">{{ $item['text'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </details>
                @endif

                <div class="guide-content">
                    {!! $tocContent !!}
                </div>

                {{-- ── CURATED LISTINGS ────────────────────────────────── --}}
                @if ($guide->listings->isNotEmpty())
                    <section class="mt-12 pt-12 border-t border-border-light">
                        <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3">Editor's Picks</p>
                        <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-2">Our picks from this guide</h2>
                        <p class="text-slate-600 text-base mb-8">{{ $guide->listings->count() }} hand-picked {{ Str::plural('place', $guide->listings->count()) }} to bring this guide to life.</p>

                        <div class="space-y-5">
                            @foreach ($guide->listings as $i => $listing)
                                @php
                                    $image = $listing->images->first();
                                    $imageUrl = null;
                                    if ($image) {
                                        $imageUrl = \str_starts_with($image->path, 'http')
                                            ? $image->path
                                            : asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $image->path), '/'));
                                    }
                                @endphp
                                <article class="bg-white border border-border-light rounded-2xl overflow-hidden hover:border-primary/40 hover:shadow-lg transition-all">
                                    <div class="grid sm:grid-cols-[260px_1fr]">
                                        <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
                                           class="block bg-slate-100 aspect-[4/3] sm:aspect-auto sm:h-full relative overflow-hidden group">
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" loading="lazy"
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                                    <span class="material-symbols-outlined" style="font-size:40px">image</span>
                                                </div>
                                            @endif
                                            <div class="absolute top-3 left-3 bg-slate-900/85 backdrop-blur text-white font-bold text-xs px-2.5 py-1 rounded-full">
                                                #{{ $i + 1 }}
                                            </div>
                                        </a>
                                        <div class="p-5 sm:p-6 flex flex-col">
                                            <div class="flex items-center gap-2 mb-2 text-xs font-bold uppercase tracking-wider text-text-secondary">
                                                <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings:'FILL' 1">{{ $listing->category->icon }}</span>
                                                <span>{{ $listing->category->name }}</span>
                                                @if ($listing->area)
                                                    <span class="text-slate-300">·</span>
                                                    <span class="inline-flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                        {{ $listing->area->name }}
                                                    </span>
                                                @endif
                                            </div>
                                            <h3 class="text-slate-900 font-bold text-xl leading-tight mb-2">
                                                <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" class="hover:text-primary transition-colors">{{ $listing->title }}</a>
                                            </h3>
                                            @if (!empty($listing->pivot->blurb))
                                                <p class="text-slate-700 text-sm leading-relaxed mb-4 italic border-l-2 border-amber-400 pl-3">
                                                    “{{ $listing->pivot->blurb }}”
                                                </p>
                                            @elseif (!empty($listing->description))
                                                <p class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-3">{{ Str::limit(strip_tags($listing->description), 180) }}</p>
                                            @endif
                                            <div class="mt-auto flex items-center justify-between gap-3">
                                                @if ($listing->price)
                                                    <span class="text-slate-900 font-bold text-base tabular-nums">₹{{ number_format((float) $listing->price, 0) }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                                <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
                                                   class="inline-flex items-center gap-1.5 text-primary font-bold text-sm hover:gap-2 transition-all">
                                                    View listing <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
            <aside class="space-y-5">
                {{-- Desktop TOC (sticky) --}}
                @if (count($tocItems) > 1)
                    <div class="hidden lg:block sticky top-24 bg-white border border-border-light rounded-2xl p-5">
                        <p class="text-slate-900 font-bold text-xs uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-primary">format_list_numbered</span>
                            In this guide
                        </p>
                        <ol class="space-y-2 text-sm">
                            @foreach ($tocItems as $i => $item)
                                <li>
                                    <a href="#{{ $item['id'] }}" class="text-slate-700 hover:text-primary transition-colors flex items-baseline gap-2 leading-snug">
                                        <span class="text-text-secondary font-semibold text-xs tabular-nums w-5">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="font-medium">{{ $item['text'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Live weather strip --}}
                <x-weather-widget variant="inline" />

                {{-- Related guides --}}
                @if ($related->isNotEmpty())
                    <div class="bg-white border border-border-light rounded-2xl p-5">
                        <p class="text-slate-900 font-bold text-xs uppercase tracking-wider mb-4">More guides</p>
                        <ul class="space-y-4">
                            @foreach ($related as $r)
                                <li>
                                    <a href="{{ route('guides.show', $r) }}" class="group flex gap-3 items-start">
                                        <div class="w-14 h-14 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0">
                                            @if ($r->heroImageUrl())
                                                <img src="{{ $r->heroImageUrl() }}" alt="" loading="lazy" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <span class="material-symbols-outlined text-[20px]">menu_book</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-slate-900 font-bold text-sm leading-snug group-hover:text-primary transition-colors line-clamp-2">{{ $r->title }}</h4>
                                            <p class="text-text-secondary text-xs mt-1">{{ $r->reading_time }} min read</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</article>
@endsection

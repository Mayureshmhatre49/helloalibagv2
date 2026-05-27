@extends('layouts.app')
@section('title', 'Best for ' . $tag->name . ' in Alibaug')
@section('meta_description', 'Discover handpicked places in Alibaug best for ' . strtolower($tag->name) . ' — curated and vetted by Hello Alibaug.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Hero --}}
    <div class="mb-8">
        <nav class="text-xs text-text-secondary mb-3 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-text-main font-medium">Best for {{ $tag->name }}</span>
        </nav>

        <div class="flex items-center gap-3 mb-3">
            @if($tag->icon)
                <span class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[26px]">{{ $tag->icon }}</span>
                </span>
            @endif
            <div>
                <p class="text-primary text-xs uppercase tracking-[0.18em] font-bold mb-0.5">Curated collection</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-text-main leading-tight">Best for {{ $tag->name }}</h1>
            </div>
        </div>
        <p class="text-text-secondary text-lg">Handpicked spots in Alibaug, vetted by Hello Alibaug.</p>
    </div>

    {{-- Listings --}}
    @if($listings->count() > 0)
        <p class="text-sm text-text-secondary mb-4">{{ $listings->total() }} {{ Str::plural('place', $listings->total()) }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($listings as $listing)
                @include('components.listing-card', ['listing' => $listing])
            @endforeach
        </div>
        <div class="mt-8">{{ $listings->links() }}</div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">explore_off</span>
            <p class="text-text-main font-medium mb-1">Nothing here yet</p>
            <p class="text-sm text-text-secondary mb-4">We're still curating places best for {{ strtolower($tag->name) }}.</p>
            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">search</span> Browse all listings
            </a>
        </div>
    @endif

    {{-- Other curated collections --}}
    @if($otherTags->isNotEmpty())
        <div class="mt-12 pt-10 border-t border-border-light">
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2">Keep exploring</p>
            <h2 class="text-slate-900 font-bold text-2xl md:text-3xl tracking-tight mb-6">More collections</h2>
            <div class="flex flex-wrap gap-2.5">
                @foreach($otherTags as $other)
                    <a href="{{ route('tag.show', $other) }}"
                       class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-primary/40 hover:shadow-md text-slate-800 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        @if($other->icon)<span class="material-symbols-outlined text-primary text-[18px]">{{ $other->icon }}</span>@endif
                        {{ $other->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

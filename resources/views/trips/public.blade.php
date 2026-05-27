@extends('layouts.app')
@section('title', $trip->name . ' — Alibaug Trip')
@section('meta_description', 'A handpicked Alibaug trip itinerary shared via Hello Alibaug.')

@push('styles')
{{-- Don't index personal trips even if public. --}}
<meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
@php
    $shareUrl = url()->current();
    $shareText = "Check out this Alibaug trip — {$trip->name}";
    $isPubliclyShared = $trip->is_public;
@endphp

<div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
    @unless ($isPubliclyShared)
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-xl px-4 py-3 mb-6 text-sm flex items-start gap-2">
            <span class="material-symbols-outlined text-amber-600 mt-0.5">lock</span>
            <p>The owner has set this trip to private. Only people with the original link can view it, and you cannot share it further.</p>
        </div>
    @endunless

    {{-- Header card --}}
    <div class="bg-white rounded-2xl border border-border-light shadow-sm overflow-hidden mb-8">
        <div class="bg-gradient-to-br from-primary/5 via-white to-amber-50 px-6 py-8">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0 flex-1">
                    <div class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-[10px] uppercase tracking-wider font-bold px-2.5 py-1 rounded-full mb-3">
                        <span class="material-symbols-outlined text-[12px]">luggage</span> Shared trip
                    </div>
                    <h1 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-3">{{ $trip->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3 text-sm text-text-secondary font-medium">
                        @if ($trip->user)
                            <span class="inline-flex items-center gap-2">
                                <img src="{{ $trip->user->getAvatarUrl() }}" alt="" class="w-6 h-6 rounded-full border border-white shadow-sm">
                                <span>Curated by <strong class="text-slate-900">{{ $trip->user->name }}</strong></span>
                            </span>
                        @endif
                        @if ($trip->date_range_label)
                            <span class="text-slate-300">·</span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                {{ $trip->date_range_label }}
                            </span>
                        @endif
                        <span class="text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">collections_bookmark</span>
                            {{ $trip->listings->count() }} {{ Str::plural('place', $trip->listings->count()) }}
                        </span>
                    </div>
                    @if ($trip->notes)
                        <p class="mt-4 text-slate-700 text-sm leading-relaxed border-l-2 border-primary/40 pl-3 whitespace-pre-line max-w-2xl">{{ $trip->notes }}</p>
                    @endif
                </div>

                @if ($isPubliclyShared)
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="https://wa.me/?text={{ rawurlencode($shareText . ' ' . $shareUrl) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">share</span> WhatsApp
                        </a>
                        <x-share-menu :url="$shareUrl" :text="$shareText" label="Share" />
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Listings --}}
    @if ($trip->listings->isEmpty())
        <div class="bg-white border border-border-light rounded-2xl p-12 text-center">
            <span class="material-symbols-outlined text-slate-300 text-[40px] mb-2">collections_bookmark</span>
            <p class="text-slate-700 font-semibold">This trip doesn't have any places yet.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($trip->listings as $i => $listing)
                @php
                    $image = $listing->images->first();
                    $imageUrl = null;
                    if ($image) {
                        $imageUrl = \str_starts_with($image->path, 'http')
                            ? $image->path
                            : asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $image->path), '/'));
                    }
                @endphp
                <article class="bg-white border border-border-light rounded-2xl overflow-hidden hover:shadow-md transition-shadow">
                    <div class="grid sm:grid-cols-[200px_1fr]">
                        <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
                           class="block aspect-[4/3] sm:aspect-auto bg-slate-100 relative overflow-hidden group">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                    <span class="material-symbols-outlined" style="font-size:32px">image</span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 bg-slate-900/85 backdrop-blur text-white font-bold text-xs px-2.5 py-1 rounded-full">
                                #{{ $i + 1 }}
                            </div>
                        </a>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-1 text-xs font-bold uppercase tracking-wider text-text-secondary">
                                <span class="material-symbols-outlined text-primary text-[14px]" style="font-variation-settings:'FILL' 1">{{ $listing->category->icon }}</span>
                                <span>{{ $listing->category->name }}</span>
                                @if ($listing->area)
                                    <span class="text-slate-300">·</span>
                                    <span class="inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">location_on</span>
                                        {{ $listing->area->name }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-slate-900 font-bold text-lg leading-tight mb-2">
                                <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" class="hover:text-primary transition-colors">{{ $listing->title }}</a>
                            </h3>
                            @if ($listing->pivot->note)
                                <p class="text-slate-700 text-sm leading-relaxed mb-3 italic border-l-2 border-amber-400 pl-3">“{{ $listing->pivot->note }}”</p>
                            @endif
                            <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
                               class="inline-flex items-center gap-1.5 text-primary font-bold text-sm hover:gap-2 transition-all">
                                View listing <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif

    {{-- Convert visitor CTA --}}
    <div class="mt-10 bg-gradient-to-br from-primary to-primary-dark text-white rounded-2xl p-8 text-center">
        <h2 class="font-serif font-bold text-2xl md:text-3xl mb-2 tracking-tight">Planning your own Alibaug trip?</h2>
        <p class="text-white/85 max-w-xl mx-auto mb-6 leading-relaxed">Save villas, restaurants, and experiences into a private trip you can share with friends — just like this one.</p>
        <a href="{{ route('trips.index') }}" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-5 py-3 rounded-xl hover:bg-white/95 transition-colors shadow-lg">
            Start a trip <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>
</div>
@endsection

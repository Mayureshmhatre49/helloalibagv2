@extends('layouts.app')
@section('title', 'My Trips')
@section('meta_description', 'Plan your Alibaug visit. Save villas, restaurants, and experiences to a shareable trip itinerary.')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">

    {{-- Header --}}
    <div class="flex items-end justify-between flex-wrap gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-text-secondary text-sm font-medium mb-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-slate-700">My Trips</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900 tracking-tight">My Trips</h1>
            <p class="text-text-secondary text-base mt-1">Save and organise places for an upcoming Alibaug visit.</p>
        </div>

        <button type="button" @click="$dispatch('open-new-trip')"
                class="inline-flex items-center gap-2 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            New trip
        </button>
    </div>

    {{-- Trips grid --}}
    @if ($trips->isEmpty())
        <div class="bg-white border border-border-light rounded-2xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[36px]">luggage</span>
            </div>
            <h2 class="text-slate-900 font-bold text-xl mb-2">Start planning your first trip</h2>
            <p class="text-text-secondary text-sm max-w-md mx-auto mb-6">Add a name and (optionally) some dates. Then add stays, restaurants, and experiences from any listing page.</p>
            <button type="button" @click="$dispatch('open-new-trip')"
                    class="inline-flex items-center gap-2 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Create a trip
            </button>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($trips as $trip)
                @php $cover = $trip->listings->first()?->images->first(); @endphp
                <a href="{{ route('trips.show', $trip) }}" class="group block bg-white border border-border-light hover:border-primary/40 hover:shadow-lg rounded-2xl overflow-hidden transition-all">
                    <div class="aspect-[16/9] bg-slate-100 overflow-hidden relative">
                        @if ($cover && $cover->path)
                            @php
                                $imageUrl = \str_starts_with($cover->path, 'http')
                                    ? $cover->path
                                    : asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $cover->path), '/'));
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $trip->name }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                <span class="material-symbols-outlined" style="font-size:40px">luggage</span>
                            </div>
                        @endif
                        @if ($trip->is_public)
                            <span class="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded-full inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[10px]">public</span> Shared
                            </span>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-slate-900 font-serif font-bold text-xl leading-tight mb-1.5 group-hover:text-primary transition-colors">{{ $trip->name }}</h3>
                        <div class="flex items-center gap-3 text-xs text-text-secondary font-medium">
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">collections_bookmark</span>
                                {{ $trip->listings_count }} {{ Str::plural('place', $trip->listings_count) }}
                            </span>
                            @if ($trip->date_range_label)
                                <span class="text-slate-300">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    {{ $trip->date_range_label }}
                                </span>
                            @endif
                            @if ($trip->party_size)
                                <span class="text-slate-300">·</span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">group</span>
                                    {{ $trip->party_size }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $trips->links() }}</div>
    @endif

    {{-- New-trip modal (dispatched via $dispatch('open-new-trip')) --}}
    <div x-data="{ open: false }"
         @open-new-trip.window="open = true"
         @keydown.escape.window="open = false">
        <div x-show="open" x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
             @click.self="open = false"
             style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-slate-900 font-serif font-bold text-xl">New trip</h2>
                    <button type="button" @click="open = false" class="text-text-secondary hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('trips.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Trip name *</label>
                        <input type="text" name="name" required autofocus maxlength="120"
                               placeholder="e.g. December weekend with friends"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">From</label>
                            <input type="date" name="start_date"
                                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">To</label>
                            <input type="date" name="end_date"
                                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Group size</label>
                        <input type="number" name="party_size" value="2" min="1" max="50"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="open = false"
                                class="text-sm font-bold text-text-secondary hover:text-slate-900 px-4 py-2 transition-colors">Cancel</button>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Create trip
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

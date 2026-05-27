@extends('layouts.app')
@section('title', $trip->name)
@section('meta_description', 'Your saved Alibaug trip with handpicked places.')

@section('content')
@php
    $shareUrl = route('trips.public', $trip->share_token);
    $shareText = "Check out my Alibaug trip — {$trip->name}";
@endphp

<div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-text-secondary text-sm font-medium mb-6" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <a href="{{ route('trips.index') }}" class="hover:text-primary transition-colors">My Trips</a>
        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
        <span class="text-slate-700 truncate">{{ Str::limit($trip->name, 50) }}</span>
    </nav>

    {{-- Header card --}}
    <div class="bg-white rounded-2xl border border-border-light shadow-sm overflow-hidden mb-8"
         x-data="{ editing: false }">
        <div class="bg-gradient-to-br from-primary/5 via-white to-amber-50 px-6 py-8 border-b border-border-light">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary text-[10px] uppercase tracking-wider font-bold px-2.5 py-1 rounded-full">
                            <span class="material-symbols-outlined text-[12px]">luggage</span> Trip
                        </span>
                        @if ($trip->is_public)
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[10px] uppercase tracking-wider font-bold px-2.5 py-1 rounded-full">
                                <span class="material-symbols-outlined text-[12px]">public</span> Shared
                            </span>
                        @endif
                    </div>
                    <h1 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-3">{{ $trip->name }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-text-secondary font-medium">
                        @if ($trip->date_range_label)
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                {{ $trip->date_range_label }}
                            </span>
                            @if ($trip->duration_label)
                                <span class="text-slate-300">·</span>
                                <span>{{ $trip->duration_label }}</span>
                            @endif
                        @endif
                        @if ($trip->party_size)
                            <span class="text-slate-300">·</span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">group</span>
                                {{ $trip->party_size }} {{ Str::plural('guest', $trip->party_size) }}
                            </span>
                        @endif
                        <span class="text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">collections_bookmark</span>
                            {{ $trip->listings->count() }} {{ Str::plural('place', $trip->listings->count()) }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    @if ($trip->is_public)
                        <a href="https://wa.me/?text={{ rawurlencode($shareText . ' ' . $shareUrl) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">share</span> WhatsApp
                        </a>
                        <x-share-menu :url="$shareUrl" :text="$shareText" label="Share" />
                    @endif
                    <button type="button" @click="editing = !editing"
                            class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-slate-300 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[14px]" x-text="editing ? 'close' : 'edit'">edit</span>
                        <span x-text="editing ? 'Cancel' : 'Edit'">Edit</span>
                    </button>
                </div>
            </div>

            @if ($trip->notes && !($trip->is_public ?? false))
                {{-- Notes display when not editing --}}
                <p x-show="!editing" class="mt-4 text-slate-700 text-sm leading-relaxed border-l-2 border-primary/40 pl-3 whitespace-pre-line">{{ $trip->notes }}</p>
            @endif
        </div>

        {{-- Edit form --}}
        <div x-show="editing" x-cloak class="bg-slate-50 border-b border-border-light px-6 py-6"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;">
            <form method="POST" action="{{ route('trips.update', $trip) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Trip name</label>
                    <input type="text" name="name" required maxlength="120" value="{{ $trip->name }}"
                           class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                </div>
                <div class="grid sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">From</label>
                        <input type="date" name="start_date" value="{{ optional($trip->start_date)->format('Y-m-d') }}"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">To</label>
                        <input type="date" name="end_date" value="{{ optional($trip->end_date)->format('Y-m-d') }}"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Group size</label>
                        <input type="number" name="party_size" min="1" max="50" value="{{ $trip->party_size }}"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-text-secondary mb-1">Notes (optional)</label>
                    <textarea name="notes" rows="3" maxlength="1000"
                              class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none"
                              placeholder="e.g. Anniversary weekend — prioritise sea view and good food.">{{ $trip->notes }}</textarea>
                </div>
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="is_public" value="1" {{ $trip->is_public ? 'checked' : '' }}
                           class="mt-1 rounded text-primary focus:ring-primary">
                    <span>
                        <span class="text-sm font-bold text-slate-900 block">Allow sharing this trip</span>
                        <span class="text-xs text-text-secondary block mt-0.5">Anyone with the link can view (read-only). Disable to keep private.</span>
                    </span>
                </label>
                <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-200">
                    <form method="POST" action="{{ route('trips.destroy', $trip) }}"
                          onsubmit="return confirm('Delete this trip? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-700 text-xs font-bold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">delete</span> Delete trip
                        </button>
                    </form>
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Listings --}}
    @if ($trip->listings->isEmpty())
        <div class="bg-white border border-border-light rounded-2xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[36px]">add_location</span>
            </div>
            <h2 class="text-slate-900 font-bold text-xl mb-2">Add your first place</h2>
            <p class="text-text-secondary text-sm max-w-md mx-auto mb-6">Browse listings, then click <strong class="text-slate-900">"Add to trip"</strong> on any property, restaurant, or experience to save it here.</p>
            <div class="flex flex-wrap items-center justify-center gap-2">
                <a href="{{ route('category.show', 'stay') }}" class="inline-flex items-center gap-1.5 bg-primary text-white font-bold text-sm px-4 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                    Browse Stays <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
                <a href="{{ route('category.show', 'eat') }}" class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-primary/40 text-slate-700 font-bold text-sm px-4 py-2.5 rounded-xl transition-colors">
                    Browse Restaurants
                </a>
            </div>
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
                <article class="bg-white border border-border-light rounded-2xl overflow-hidden hover:border-primary/30 hover:shadow-md transition-all">
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
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="min-w-0 flex-1">
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
                                    <h3 class="text-slate-900 font-bold text-lg leading-tight">
                                        <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" class="hover:text-primary transition-colors">{{ $listing->title }}</a>
                                    </h3>
                                </div>
                                <form method="POST" action="{{ route('trips.detach', [$trip, $listing]) }}"
                                      onsubmit="return confirm('Remove from this trip?')" class="flex-shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-text-secondary hover:text-red-600 transition-colors p-1.5 rounded-lg hover:bg-red-50"
                                            title="Remove from trip">
                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                    </button>
                                </form>
                            </div>

                            {{-- Per-listing note --}}
                            <details class="mt-3 group">
                                <summary class="cursor-pointer text-xs font-bold text-text-secondary hover:text-primary transition-colors inline-flex items-center gap-1 list-none">
                                    <span class="material-symbols-outlined text-[14px] group-open:rotate-180 transition-transform">expand_more</span>
                                    {{ $listing->pivot->note ? 'Edit note' : 'Add a note' }}
                                </summary>
                                <form method="POST" action="{{ route('trips.note', [$trip, $listing]) }}" class="mt-2 flex gap-2">
                                    @csrf
                                    <input type="text" name="note" maxlength="500"
                                           placeholder="e.g. Try the seafood thali"
                                           value="{{ $listing->pivot->note }}"
                                           class="flex-1 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-3 py-2 rounded-lg transition-colors">
                                        Save
                                    </button>
                                </form>
                                @if ($listing->pivot->note)
                                    <p class="mt-2 text-sm text-slate-700 italic">“{{ $listing->pivot->note }}”</p>
                                @endif
                            </details>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>
@endsection

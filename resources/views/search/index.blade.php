@extends('layouts.app')
@section('title', 'Search Listings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Search Header --}}
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="max-w-3xl" id="searchForm">
            <div class="flex bg-white rounded-xl border border-border-light overflow-hidden shadow-sm">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search villas, restaurants, events..."
                           class="w-full pl-12 pr-4 py-3.5 text-text-main border-0 focus:ring-0 text-sm">
                </div>
                <button type="submit" class="bg-primary text-white px-6 font-medium text-sm hover:bg-primary-dark transition-colors">Search</button>
            </div>
            {{-- Hidden fields for active filters --}}
            @if(!empty($filters['category_id']))<input type="hidden" name="category_id" value="{{ $filters['category_id'] }}">@endif
            @if(!empty($filters['area_id']))<input type="hidden" name="area_id" value="{{ $filters['area_id'] }}">@endif
            @if(!empty($filters['sort']))<input type="hidden" name="sort" value="{{ $filters['sort'] }}">@endif
            @if(!empty($filters['verified_only']))<input type="hidden" name="verified_only" value="1">@endif
            @foreach($filters['tags'] ?? [] as $tagId)<input type="hidden" name="tags[]" value="{{ $tagId }}">@endforeach
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filters Sidebar --}}
        <aside class="lg:w-72 flex-shrink-0" x-data="{ mobileFilters: false }">
            <button @click="mobileFilters = !mobileFilters" class="lg:hidden flex items-center gap-2 bg-white border border-border-light px-4 py-2.5 rounded-xl text-sm font-medium mb-4 w-full justify-center">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filters
            </button>
            <div :class="mobileFilters ? 'block' : 'hidden lg:block'">
                <form action="{{ route('search') }}" method="GET" class="space-y-5">
                    <input type="hidden" name="q" value="{{ $query }}">

                    {{-- Sort --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Sort By</h3>
                        <select name="sort" onchange="this.form.submit()" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                            <option value="newest" {{ ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ ($filters['sort'] ?? '') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Category</h3>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                                <label class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer hover:text-text-main">
                                    <input type="radio" name="category_id" value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'checked' : '' }}
                                           class="text-primary focus:ring-primary/20" onchange="this.form.submit()">
                                    <span class="material-symbols-outlined text-[16px]">{{ $cat->icon }}</span>
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Area --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Area</h3>
                        <select name="area_id" onchange="this.form.submit()" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-white">
                            <option value="">All Areas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ ($filters['area_id'] ?? '') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Price Range</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min ₹"
                                   class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max ₹"
                                   class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        </div>
                        <button type="submit" class="w-full mt-2 bg-background-light text-text-main py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition-colors">Apply Price</button>
                    </div>

                    {{-- Trust filter --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Trust</h3>
                        <label class="flex items-start gap-2.5 cursor-pointer group">
                            <input type="checkbox" name="verified_only" value="1"
                                   {{ !empty($filters['verified_only']) ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-primary focus:ring-primary/20"
                                   onchange="this.form.submit()">
                            <span class="text-sm text-text-secondary group-hover:text-text-main">
                                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-emerald-500 text-[16px]" style="font-variation-settings:'FILL' 1">verified</span>
                                    Verified only
                                </span>
                                <span class="block text-[11px] mt-0.5">Show only listings reviewed and vetted by Hello Alibaug.</span>
                            </span>
                        </label>
                    </div>

                    {{-- Amenities --}}
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Amenities</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($amenities as $amenity)
                                <label class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer hover:text-text-main">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                           {{ in_array($amenity->id, $filters['amenities'] ?? []) ? 'checked' : '' }}
                                           class="rounded text-primary focus:ring-primary/20" onchange="this.form.submit()">
                                    <span class="material-symbols-outlined text-[14px]">{{ $amenity->icon }}</span>
                                    {{ ucwords(strtolower(trim($amenity->name))) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Best For tags --}}
                    @if($tags->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-border-light p-4">
                        <h3 class="text-sm font-bold text-text-main mb-3">Best For</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($tags as $tag)
                                <label class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer hover:text-text-main">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                           {{ in_array($tag->id, $filters['tags'] ?? []) ? 'checked' : '' }}
                                           class="rounded text-primary focus:ring-primary/20" onchange="this.form.submit()">
                                    @if($tag->icon)<span class="material-symbols-outlined text-[14px]">{{ $tag->icon }}</span>@endif
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Clear Filters --}}
                    <a href="{{ route('search', ['q' => $query]) }}" class="block text-center text-sm text-primary font-medium hover:underline">Clear All Filters</a>
                </form>
            </div>
        </aside>

        {{-- Results --}}
        <div class="flex-1">
            <div class="flex items-center justify-between gap-4 mb-4 flex-wrap">
                <p class="text-sm text-text-secondary">
                    @if($results->total() > 0)
                        {{ $results->total() }} {{ Str::plural('result', $results->total()) }}
                        @if($query) for "<strong class="text-text-main">{{ $query }}</strong>"@endif
                    @elseif($query)
                        No results for "<strong class="text-text-main">{{ $query }}</strong>"
                    @else
                        Browse all listings
                    @endif
                </p>

                <div class="flex items-center gap-2">
                    @php
                        $searchShareText = trim(
                            'Found these on Hello Alibaug' .
                            ($query ? " for \"{$query}\"" : '')
                        );
                    @endphp
                    <x-share-menu :text="$searchShareText" label="Share" />

                    <div class="inline-flex bg-slate-100 rounded-xl p-1" role="tablist" aria-label="View toggle">
                        <span class="bg-white shadow-sm text-slate-900 font-bold text-xs px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 cursor-default" aria-current="true">
                            <span class="material-symbols-outlined text-[16px]">view_list</span>
                            List
                        </span>
                        <a href="{{ route('map.index') }}"
                           class="text-slate-600 hover:text-slate-900 font-semibold text-xs px-3 py-1.5 rounded-lg inline-flex items-center gap-1.5 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">map</span>
                            Map
                        </a>
                    </div>
                </div>
            </div>

            @if($results->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($results as $listing)
                        @include('components.listing-card', ['listing' => $listing])
                    @endforeach
                </div>
                <div class="mt-8">{{ $results->links() }}</div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">search_off</span>
                    <p class="text-text-main font-medium mb-1">No results found</p>
                    <p class="text-sm text-text-secondary mb-4">Try different keywords, remove filters, or browse categories.</p>
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span> Reset Search
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

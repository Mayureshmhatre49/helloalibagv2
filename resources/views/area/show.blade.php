@extends('layouts.app')
@section('title', $area->name . ', Alibaug — Stays, Dining & Experiences')
@section('meta_description', 'Explore ' . $area->name . ' in Alibaug — find stays, restaurants, experiences and real estate in ' . $area->name . '. Verified local listings with photos and prices on Hello Alibaug.')
@section('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}" },
    { "@type": "ListItem", "position": 2, "name": "{{ addslashes($area->name) }}, Alibaug", "item": "{{ url()->current() }}" }
  ]
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Area Hero --}}
    <div class="mb-8 bg-white rounded-2xl border border-border-light p-8 text-center relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-text-main mb-3">Explore {{ $area->name }}</h1>
            <p class="text-text-secondary max-w-2xl mx-auto">{{ $area->description ?? 'Discover the best stays, dining, and experiences in ' . $area->name . ', Alibaug. Browse our curated listings to plan your perfect trip.' }}</p>
        </div>
    </div>

    {{-- Search Header --}}
    <div class="mb-8">
        <form action="{{ route('area.show', $area->slug) }}" method="GET" class="max-w-3xl" id="searchForm">
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
                <form action="{{ route('area.show', $area->slug) }}" method="GET" class="space-y-5">
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

                    {{-- Area (Hidden / Fixed for Landing Page) --}}
                    <input type="hidden" name="area_id" value="{{ $area->id }}">

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

                    {{-- Clear Filters --}}
                    <a href="{{ route('area.show', $area->slug) }}" class="block text-center text-sm text-primary font-medium hover:underline">Clear All Filters</a>
                </form>
            </div>
        </aside>

        {{-- Results --}}
        <div class="flex-1">
            <div class="flex items-center justify-between mb-4">
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
            </div>

            @if($results->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($results as $listing)
                        @include('components.listing-card', ['listing' => $listing])
                    @endforeach
                </div>
                <div class="mt-8">{{ $results->links() }}</div>

                {{-- Internal links to programmatic category-in-area landing pages --}}
                @php
                    $areaCategoryLinks = \App\Models\Category::where('is_active', true)
                        ->whereHas('listings', fn ($q) => $q->approved()->where('area_id', $area->id))
                        ->orderBy('sort_order')
                        ->get();
                @endphp
                @if ($areaCategoryLinks->isNotEmpty())
                    <div class="mt-12 pt-10 border-t border-border-light">
                        <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2">Browse by category</p>
                        <h2 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl tracking-tight mb-6">What to do in {{ $area->name }}</h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($areaCategoryLinks as $linkCat)
                                <a href="{{ url('/' . $linkCat->slug . '-in-' . $area->slug) }}"
                                   class="group bg-white border border-border-light hover:border-primary/40 hover:shadow-md rounded-xl px-4 py-3 flex items-center gap-3 transition-all">
                                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings:'FILL' 1">{{ $linkCat->icon }}</span>
                                    </div>
                                    <span class="text-slate-900 font-bold text-sm flex-1">{{ $linkCat->name }} in {{ $area->name }}</span>
                                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary group-hover:translate-x-0.5 transition-all text-[18px]">arrow_forward</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">search_off</span>
                    <p class="text-text-main font-medium mb-1">No results found</p>
                    <p class="text-sm text-text-secondary mb-4">Try different keywords, remove filters, or browse categories.</p>
                    <a href="{{ route('area.show', $area->slug) }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span> Reset Search
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

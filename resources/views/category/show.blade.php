@extends('layouts.app')
@section('title', $category->name . ' in Alibaug')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-main mb-2">{{ $category->name }}</h1>
        <p class="text-text-secondary">{{ $category->description }}</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filter Sidebar --}}
        <aside class="lg:w-72 flex-shrink-0" x-data="{ filtersOpen: false }">
            <button @click="filtersOpen = !filtersOpen" class="lg:hidden w-full flex items-center justify-between bg-white rounded-xl border border-border-light px-4 py-3 mb-4">
                <span class="text-sm font-medium">Filters</span>
                <span class="material-symbols-outlined text-[20px]" x-text="filtersOpen ? 'expand_less' : 'expand_more'">expand_more</span>
            </button>

            <form method="GET" action="{{ route('category.show', $category) }}" class="space-y-6" :class="filtersOpen ? 'block' : 'hidden lg:block'">
                <div class="bg-white rounded-2xl border border-border-light p-5">
                    {{-- Search within category --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-text-main mb-2">Search</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search listings..." class="w-full rounded-lg border-border-light text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                    </div>

                    {{-- Area Filter --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-text-main mb-2">Area</label>
                        <select name="area_id" class="w-full rounded-lg border-border-light text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                            <option value="">All Areas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}" {{ ($filters['area_id'] ?? '') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Range --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-text-main mb-2">Price Range</label>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" class="w-1/2 rounded-lg border-border-light text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                            <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" class="w-1/2 rounded-lg border-border-light text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-text-main mb-2">Sort By</label>
                        <select name="sort" class="w-full rounded-lg border-border-light text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                            <option value="newest" {{ ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                            <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                            <option value="rating" {{ ($filters['sort'] ?? '') == 'rating' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">Apply Filters</button>
                    <a href="{{ route('category.show', $category) }}" class="block text-center text-sm text-text-secondary mt-2 hover:text-primary">Clear Filters</a>
                </div>
            </form>
        </aside>

        {{-- Listings Grid --}}
        <div class="flex-1">
            @if($listings->count() > 0)
                <p class="text-sm text-text-secondary mb-4">{{ $listings->total() }} listings found</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($listings as $listing)
                        @include('components.listing-card', ['listing' => $listing])
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $listings->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">search_off</span>
                    <p class="text-text-main font-medium mb-1">No listings found</p>
                    <p class="text-sm text-text-secondary">Try adjusting your filters or check back later.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

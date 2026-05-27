@extends('layouts.app')
@section('title', ($activeCategory->name ?? 'Marketplace') . ' for sale in Alibaug')
@section('meta_description', 'Buy and sell ' . strtolower($activeCategory->name ?? 'used items') . ' in Alibaug — a clean, moderated local marketplace by Hello Alibaug.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <p class="text-primary text-xs uppercase tracking-[0.18em] font-bold mb-1">Alibaug Marketplace</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-text-main">{{ $activeCategory->name ?? 'Buy & Sell in Alibaug' }}</h1>
            <p class="text-text-secondary mt-1">Locally owned items, every listing reviewed by our team.</p>
        </div>
        <a href="{{ route('marketplace.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors shadow">
            <span class="material-symbols-outlined text-[18px]">add_circle</span> Sell an item
        </a>
    </div>

    {{-- Category chips --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('marketplace.index') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-semibold transition-colors {{ !$activeCategory ? 'bg-primary text-white' : 'bg-white border border-border-light text-slate-700 hover:border-primary/40' }}">
            All
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('marketplace.index', ['category' => $cat->slug]) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-semibold transition-colors {{ optional($activeCategory)->id === $cat->id ? 'bg-primary text-white' : 'bg-white border border-border-light text-slate-700 hover:border-primary/40' }}">
                @if($cat->icon)<span class="material-symbols-outlined text-[16px]">{{ $cat->icon }}</span>@endif
                {{ $cat->name }}
                <span class="text-[10px] opacity-70">{{ $cat->active_classifieds_count }}</span>
            </a>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filters --}}
        <aside class="lg:w-64 flex-shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="lg:hidden flex items-center gap-2 bg-white border border-border-light px-4 py-2.5 rounded-xl text-sm font-medium mb-4 w-full justify-center">
                <span class="material-symbols-outlined text-[18px]">tune</span> Filters
            </button>
            <form action="{{ route('marketplace.index') }}" method="GET" class="space-y-4" :class="open ? 'block' : 'hidden lg:block'">
                @if($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory->slug }}">@endif

                <div class="bg-white rounded-2xl border border-border-light p-4">
                    <label class="block text-sm font-bold text-text-main mb-2">Search</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="What are you looking for?"
                           class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:border-primary focus:ring-primary">
                </div>

                <div class="bg-white rounded-2xl border border-border-light p-4">
                    <label class="block text-sm font-bold text-text-main mb-2">Sort</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm bg-white">
                        <option value="newest" {{ ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="popular" {{ ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>

                <div class="bg-white rounded-2xl border border-border-light p-4">
                    <label class="block text-sm font-bold text-text-main mb-2">Area</label>
                    <select name="area_id" onchange="this.form.submit()" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm bg-white">
                        <option value="">All Areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ ($filters['area_id'] ?? '') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white rounded-2xl border border-border-light p-4">
                    <label class="block text-sm font-bold text-text-main mb-2">Condition</label>
                    <select name="condition" onchange="this.form.submit()" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm bg-white">
                        <option value="">Any</option>
                        @foreach(\App\Models\Classified::CONDITIONS as $key => $label)
                            <option value="{{ $key }}" {{ ($filters['condition'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white rounded-2xl border border-border-light p-4">
                    <label class="block text-sm font-bold text-text-main mb-2">Price (₹)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm">
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm">
                    </div>
                    <button type="submit" class="w-full mt-2 bg-background-light text-text-main py-2 rounded-xl text-xs font-medium hover:bg-gray-200">Apply</button>
                </div>

                <a href="{{ route('marketplace.index') }}" class="block text-center text-sm text-primary font-medium hover:underline">Clear filters</a>
            </form>
        </aside>

        {{-- Results --}}
        <div class="flex-1">
            <p class="text-sm text-text-secondary mb-4">{{ $classifieds->total() }} {{ Str::plural('item', $classifieds->total()) }} for sale</p>
            @if($classifieds->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($classifieds as $item)
                        @include('components.classified-card', ['item' => $item])
                    @endforeach
                </div>
                <div class="mt-8">{{ $classifieds->links() }}</div>
            @else
                <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
                    <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">sell</span>
                    <p class="text-text-main font-medium mb-1">Nothing for sale here yet</p>
                    <p class="text-sm text-text-secondary mb-4">Be the first to list an item in this category.</p>
                    <a href="{{ route('marketplace.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span> Sell an item
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

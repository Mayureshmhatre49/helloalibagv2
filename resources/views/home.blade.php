@extends('layouts.app')
@section('title', 'Premium Local Marketplace — Stays, Dining & Real Estate')
@section('meta_desc', 'Discover curated stays, premium real estate, dining, events and local experiences in Alibaug. Hello Alibaug is your gateway to coastal luxury.')

@section('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Hello Alibaug",
  "url": "{{ url('/') }}",
  "telephone": "+919876543210",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Alibaug",
    "addressRegion": "Maharashtra",
    "addressCountry": "IN"
  },
  "description": "Premium Local Marketplace for Stays, Dining & Real Estate in Alibaug."
}
</script>
@endsection

@section('content')

{{-- ═══════════════════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════════════════ --}}
<section class="relative w-full h-[88vh] min-h-[520px] max-h-[760px]">
    {{-- Background image --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/hello-alibaug-banner.png') }}"
             alt="Hello Alibaug — Coastal Luxury"
             class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/25 to-black/75"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 h-full max-w-[1280px] mx-auto px-4 sm:px-6 flex flex-col items-center justify-center text-center">

        {{-- Pill label --}}
        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-md border border-white/20 text-white px-4 py-1.5 rounded-full text-xs font-bold mb-5 tracking-wider uppercase">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
            Alibaug's #1 Local Marketplace
        </div>

        <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg leading-tight max-w-3xl">
            Experience<br class="hidden sm:block"> Coastal Luxury
        </h1>
        <p class="text-base sm:text-lg text-white/85 max-w-xl mb-8 font-light leading-relaxed px-2">
            Curated stays, premium real estate, dining & experiences in Alibaug — all in one place.
        </p>

        {{-- Search card --}}
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-data="{ activeTab: '{{ $categories->first()?->id ?? 1 }}' }">

            {{-- Category tabs --}}
            <div class="flex overflow-x-auto hide-scrollbar border-b border-slate-100">
                @foreach($categories as $cat)
                    <button type="button"
                            @click="activeTab = '{{ $cat->id }}'"
                            :class="activeTab === '{{ $cat->id }}'
                                ? 'text-primary border-b-2 border-primary bg-primary/5 font-bold'
                                : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
                            class="flex-shrink-0 sm:flex-1 py-3.5 px-4 sm:px-5 text-xs sm:text-sm whitespace-nowrap transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] sm:text-[18px]"
                              style="font-variation-settings:'FILL' 1">{{ $cat->icon }}</span>
                        <span class="hidden sm:inline">{{ $cat->name }}</span>
                        <span class="sm:hidden">{{ Str::limit($cat->name, 7) }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Search row --}}
            <form action="{{ route('search') }}" method="GET" class="p-3 sm:p-4">
                <input type="hidden" name="category_id" x-bind:value="activeTab">
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <div class="flex-grow relative border border-slate-200 rounded-xl bg-slate-50 hover:bg-white focus-within:bg-white focus-within:border-primary/40 focus-within:ring-2 focus-within:ring-primary/15 transition-all">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-primary/70 text-[22px]">search</span>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider absolute top-2 left-12">Search</label>
                        <input type="text" name="q"
                               class="w-full pl-12 pr-4 pt-6 pb-2 bg-transparent border-none text-slate-900 placeholder-slate-400 text-sm sm:text-base font-medium focus:ring-0"
                               placeholder="Villas, restaurants, activities…">
                    </div>
                    <button type="submit"
                            class="sm:w-auto bg-primary hover:bg-primary/90 active:scale-95 text-white rounded-xl px-8 py-3.5 font-bold text-sm shadow-lg shadow-primary/30 flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        Search
                    </button>
                </div>
            </form>
        </div>

        {{-- Quick area links below search --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mt-5">
            <span class="text-white/50 text-xs">Popular:</span>
            @foreach($areas->take(5) as $area)
                <a href="{{ route('search', ['area_id' => $area->id]) }}"
                   class="text-white/80 hover:text-white text-xs font-medium border border-white/20 hover:border-white/50 px-3 py-1 rounded-full transition-colors backdrop-blur-sm">
                    {{ $area->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     LIVE STATS BAND
═══════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-b border-slate-100">
    <div class="max-w-[1280px] mx-auto px-4 py-4 sm:py-5 flex flex-wrap justify-center gap-6 sm:gap-16">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings:'FILL' 1">verified</span>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-none">{{ $totalListings > 0 ? $totalListings . '+' : 'All' }} Listings</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Verified & curated</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-emerald-600 text-[18px]" style="font-variation-settings:'FILL' 1">location_on</span>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-none">{{ $totalAreas }}+ Areas</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Across the coast</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-amber-500 text-[18px]" style="font-variation-settings:'FILL' 1">add_business</span>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-none">Free to List</p>
                <p class="text-[11px] text-slate-500 mt-0.5">No upfront cost</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-violet-500 text-[18px]" style="font-variation-settings:'FILL' 1">support_agent</span>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-none">Local Experts</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Real Alibaug knowledge</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     EXPLORE CATEGORIES
═══════════════════════════════════════════════════════════════ --}}
@php
$catConfig = [
    'stay'        => ['icon_bg' => 'bg-blue-50',    'icon_text' => 'text-blue-600',   'border' => 'border-blue-100',   'hover_bg' => 'hover:bg-blue-50/60'],
    'eat'         => ['icon_bg' => 'bg-rose-50',    'icon_text' => 'text-rose-500',   'border' => 'border-rose-100',   'hover_bg' => 'hover:bg-rose-50/60'],
    'events'      => ['icon_bg' => 'bg-purple-50',  'icon_text' => 'text-purple-500', 'border' => 'border-purple-100', 'hover_bg' => 'hover:bg-purple-50/60'],
    'explore'     => ['icon_bg' => 'bg-emerald-50', 'icon_text' => 'text-emerald-600','border' => 'border-emerald-100','hover_bg' => 'hover:bg-emerald-50/60'],
    'services'    => ['icon_bg' => 'bg-teal-50',    'icon_text' => 'text-teal-500',   'border' => 'border-teal-100',   'hover_bg' => 'hover:bg-teal-50/60'],
    'real-estate' => ['icon_bg' => 'bg-orange-50',  'icon_text' => 'text-orange-500', 'border' => 'border-orange-100', 'hover_bg' => 'hover:bg-orange-50/60'],
];
@endphp
<section class="py-12 sm:py-16 max-w-[1280px] mx-auto px-4">
    <div class="flex items-end justify-between mb-8 sm:mb-10">
        <div>
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Discover</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">What are you looking for?</h2>
        </div>
        <a href="{{ route('search') }}"
           class="hidden sm:flex items-center gap-1 text-sm font-bold text-primary hover:underline">
            Browse all <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach($categories as $cat)
            @php $cfg = $catConfig[$cat->slug] ?? ['icon_bg' => 'bg-slate-50', 'icon_text' => 'text-slate-600', 'border' => 'border-slate-100', 'hover_bg' => 'hover:bg-slate-50']; @endphp
            <a href="{{ route('category.show', $cat) }}"
               class="group relative flex flex-col p-4 sm:p-5 rounded-2xl bg-white border {{ $cfg['border'] }} {{ $cfg['hover_bg'] }} hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">

                {{-- Icon --}}
                <div class="w-12 h-12 rounded-2xl {{ $cfg['icon_bg'] }} {{ $cfg['icon_text'] }} flex items-center justify-center mb-3.5 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[26px]"
                          style="font-variation-settings:'FILL' 1">{{ $cat->icon }}</span>
                </div>

                {{-- Name & count --}}
                <p class="font-bold text-slate-900 text-sm leading-snug">{{ $cat->name }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    {{ $cat->listings_count > 0 ? $cat->listings_count . ' listing' . ($cat->listings_count !== 1 ? 's' : '') : 'Coming soon' }}
                </p>

                {{-- Arrow --}}
                <span class="material-symbols-outlined text-[16px] {{ $cfg['icon_text'] }} absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 translate-x-1 group-hover:translate-x-0 transition-all duration-200">arrow_forward</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     FEATURED LISTINGS
═══════════════════════════════════════════════════════════════ --}}
<section class="py-10 sm:py-14 bg-slate-50/60">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Handpicked</p>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">Featured in Alibaug</h2>
                <p class="text-slate-500 text-sm mt-1">Top-rated, admin-verified picks across all categories.</p>
            </div>
            <a href="{{ route('search', ['sort' => 'rating']) }}"
               class="hidden sm:flex items-center gap-1 text-sm font-bold text-primary hover:underline flex-shrink-0">
                View all <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        @if($featuredListings->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($featuredListings as $listing)
                    @include('components.listing-card', ['listing' => $listing])
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <span class="material-symbols-outlined text-5xl mb-3 block">home_work</span>
                <p class="font-medium">Featured listings coming soon.</p>
            </div>
        @endif

        <div class="sm:hidden mt-6 text-center">
            <a href="{{ route('search') }}" class="inline-flex items-center gap-1 text-primary font-bold text-sm hover:underline">
                View all listings <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     TRENDING COLLECTIONS  (3 editorial cards — category links)
═══════════════════════════════════════════════════════════════ --}}
<section class="py-12 sm:py-16 max-w-[1280px] mx-auto px-4">
    <div class="mb-8">
        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Collections</p>
        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">Trending in Alibaug</h2>
        <p class="text-slate-500 text-sm mt-1">Curated collections for every kind of trip.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        {{-- Stays --}}
        <a href="{{ route('category.show', 'stay') }}"
           class="group relative h-64 sm:h-80 rounded-2xl overflow-hidden block">
            <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&auto=format&fit=crop&q=80"
                 alt="Beachfront Villas" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-5 sm:p-6">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full mb-2">Stays</span>
                <h3 class="text-white text-lg sm:text-xl font-bold font-serif">Beachfront Villas</h3>
                <p class="text-white/70 text-xs mt-1 flex items-center gap-1">
                    Explore collection <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </p>
            </div>
        </a>

        {{-- Dining --}}
        <a href="{{ route('category.show', 'eat') }}"
           class="group relative h-64 sm:h-80 rounded-2xl overflow-hidden block">
            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&auto=format&fit=crop&q=80"
                 alt="Dining & Restaurants" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-5 sm:p-6">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full mb-2">Dining</span>
                <h3 class="text-white text-lg sm:text-xl font-bold font-serif">Seafood & Fine Dining</h3>
                <p class="text-white/70 text-xs mt-1 flex items-center gap-1">
                    Explore collection <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </p>
            </div>
        </a>

        {{-- Real Estate --}}
        <a href="{{ route('category.show', 'real-estate') }}"
           class="group relative h-64 sm:h-80 rounded-2xl overflow-hidden block sm:col-span-2 lg:col-span-1">
            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80"
                 alt="Coastal Properties" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-5 sm:p-6">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full mb-2">Real Estate</span>
                <h3 class="text-white text-lg sm:text-xl font-bold font-serif">Coastal Dream Homes</h3>
                <p class="text-white/70 text-xs mt-1 flex items-center gap-1">
                    Explore collection <span class="material-symbols-outlined text-[14px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </p>
            </div>
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     REAL ESTATE SPOTLIGHT  (dark editorial section)
═══════════════════════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 bg-[#0b1e3d] text-white relative overflow-hidden">
    {{-- decorative blob --}}
    <div class="absolute top-0 right-0 w-1/2 h-full opacity-5 pointer-events-none hidden sm:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.1,-19.2,95.8,-5.1C93.5,9,82.2,22.4,70.9,33.6C59.6,44.8,48.3,53.8,36.4,62.8C24.5,71.8,12,80.8,-0.2,81.1C-12.3,81.4,-24.9,73,-36.4,63.7C-47.9,54.4,-58.3,44.2,-67.6,31.7C-76.9,19.2,-85.1,4.4,-82.9,-9.1C-80.7,-22.6,-68.1,-34.8,-55.8,-43.8C-43.5,-52.8,-31.5,-58.6,-19.4,-67.2C-7.3,-75.8,4.9,-87.2,16.5,-86.6C28.1,-86,30.5,-69.6,44.7,-76.4Z" fill="#FFFFFF" transform="translate(100 100)"></path>
        </svg>
    </div>

    <div class="max-w-[1280px] mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-10 sm:gap-16">

            {{-- Text side --}}
            <div class="lg:w-1/2">
                <span class="text-primary font-bold uppercase tracking-widest text-xs sm:text-sm mb-3 block">Real Estate Spotlight</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold mb-4 sm:mb-6 leading-tight">
                    Find Your Coastal<br>Dream Home
                </h2>
                <p class="text-slate-300 text-sm sm:text-base mb-8 leading-relaxed max-w-md">
                    Exclusive access to Alibaug's most coveted land parcels and luxury villas. Whether a vacation retreat or a long-term investment — our local experts guide you every step.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('category.show', 'real-estate') }}"
                       class="bg-primary hover:bg-primary/90 text-white px-7 py-3 rounded-xl font-bold transition-all shadow-lg shadow-primary/20 text-center text-sm">
                        Browse Properties
                    </a>
                    <a href="{{ route('page.contact') }}"
                       class="border border-slate-500 hover:border-white text-white px-7 py-3 rounded-xl font-bold transition-all text-center text-sm">
                        Talk to an Expert
                    </a>
                </div>

                {{-- Feature list --}}
                <div class="mt-8 grid grid-cols-2 gap-3">
                    @foreach(['Verified land records','Local legal guidance','Coastal zoning expertise','Transparent pricing'] as $f)
                    <div class="flex items-center gap-2 text-sm text-slate-300">
                        <span class="material-symbols-outlined text-emerald-400 text-[16px]" style="font-variation-settings:'FILL' 1">check_circle</span>
                        {{ $f }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Image collage --}}
            <div class="lg:w-1/2 w-full">
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?w=600&auto=format&fit=crop&q=80"
                         alt="Luxury coastal villa"
                         class="rounded-2xl w-full h-44 sm:h-64 object-cover translate-y-5 sm:translate-y-10 shadow-2xl"
                         loading="lazy">
                    <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=600&auto=format&fit=crop&q=80"
                         alt="Modern coastal home"
                         class="rounded-2xl w-full h-44 sm:h-64 object-cover shadow-2xl"
                         loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     EXPLORE BY AREA
═══════════════════════════════════════════════════════════════ --}}
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="mb-8 sm:mb-10 text-center">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Locations</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">Explore by Area</h2>
            <p class="text-slate-500 text-sm mt-1">Every beach, village and neighbourhood has its own character.</p>
        </div>

        @php
        $areaImages = [
            'Mandwa'       => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&auto=format&fit=crop&q=80',
            'Alibaug'      => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=80',
            'Alibaug Town' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&auto=format&fit=crop&q=80',
            'Awas'         => 'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?w=800&auto=format&fit=crop&q=80',
            'Kihim'        => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&auto=format&fit=crop&q=80',
            'Nagaon'       => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=800&auto=format&fit=crop&q=80',
            'Varsoli'      => 'https://images.unsplash.com/photo-1596178065887-f273200ff960?w=800&auto=format&fit=crop&q=80',
            'Thal'         => 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?w=800&auto=format&fit=crop&q=80',
        ];
        $fallback = 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&auto=format&fit=crop&q=80';
        $displayAreas = $areas->take(6);
        @endphp

        @if($displayAreas->count() >= 3)
        {{-- Masonry-style grid that works for 3–6 areas --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 auto-rows-[180px] sm:auto-rows-[240px]">
            @foreach($displayAreas as $idx => $area)
                @php
                    $img = $area->image ?? ($areaImages[$area->name] ?? $fallback);
                    $wide = ($idx === 0 || $idx === 3) && $displayAreas->count() >= 4;
                @endphp
                <a href="{{ route('search', ['area_id' => $area->id]) }}"
                   class="group relative rounded-xl sm:rounded-2xl overflow-hidden block {{ $wide ? 'md:col-span-2' : '' }}">
                    <img src="{{ $img }}"
                         alt="{{ $area->name }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 to-transparent"></div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                    <div class="absolute bottom-3 left-3 sm:bottom-5 sm:left-5 text-white">
                        <h3 class="text-base sm:text-xl font-bold font-serif">{{ $area->name }}</h3>
                        @if($area->tagline)
                            <p class="text-xs text-white/75 mt-0.5 hidden sm:block">{{ $area->tagline }}</p>
                        @endif
                    </div>
                    <div class="absolute bottom-3 right-3 sm:bottom-5 sm:right-5">
                        <span class="material-symbols-outlined text-white/60 group-hover:text-white text-[20px] transition-colors">arrow_forward</span>
                    </div>
                </a>
            @endforeach
        </div>
        @else
        {{-- Fallback for 1–2 areas: simple equal-width cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($displayAreas as $area)
                @php $img = $area->image ?? ($areaImages[$area->name] ?? $fallback); @endphp
                <a href="{{ route('search', ['area_id' => $area->id]) }}"
                   class="group relative rounded-2xl overflow-hidden h-52 block">
                    <img src="{{ $img }}" alt="{{ $area->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 to-transparent"></div>
                    <div class="absolute bottom-5 left-5 text-white">
                        <h3 class="text-xl font-bold font-serif">{{ $area->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('search') }}"
               class="inline-flex items-center gap-2 border border-slate-200 hover:border-primary text-slate-700 hover:text-primary px-6 py-2.5 rounded-xl font-bold text-sm transition-colors">
                View all areas <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════════════════ --}}
<section class="py-12 sm:py-16 bg-slate-50">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="text-center mb-10">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Simple & Transparent</p>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">How Hello Alibaug Works</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 max-w-4xl mx-auto">
            @foreach([
                ['icon' => 'search', 'color' => 'bg-blue-50 text-blue-600', 'step' => '01', 'title' => 'Search & Discover', 'desc' => 'Browse verified listings across stays, dining, real estate, events and more — all filtered by area and category.'],
                ['icon' => 'chat', 'color' => 'bg-emerald-50 text-emerald-600', 'step' => '02', 'title' => 'Connect with Owners', 'desc' => 'Send an inquiry or message the owner directly. Get real answers from local experts who know Alibaug.'],
                ['icon' => 'beach_access', 'color' => 'bg-amber-50 text-amber-600', 'step' => '03', 'title' => 'Plan Your Visit', 'desc' => 'Confirm your booking, get directions, and enjoy your Alibaug experience with full confidence.'],
            ] as $step)
            <div class="flex flex-col items-center text-center sm:items-start sm:text-left">
                <div class="relative mb-4">
                    <div class="w-14 h-14 rounded-2xl {{ $step['color'] }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px]" style="font-variation-settings:'FILL' 1">{{ $step['icon'] }}</span>
                    </div>
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">{{ $step['step'] }}</span>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1.5">{{ $step['title'] }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     STORIES FROM THE COAST  (dynamic blog)
═══════════════════════════════════════════════════════════════ --}}
@if($recentPosts->count())
<section class="py-12 sm:py-16 bg-white">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Journal</p>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">Stories from the Coast</h2>
            </div>
            <a href="{{ route('blog.index') }}"
               class="hidden sm:flex items-center gap-1 text-sm font-bold text-primary hover:underline">
                All articles <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                <div class="rounded-2xl overflow-hidden aspect-[16/9] mb-4 bg-slate-100">
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}"
                             alt="{{ $post->featured_image_alt ?? $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-100">
                            <span class="material-symbols-outlined text-4xl text-slate-300">article</span>
                        </div>
                    @endif
                </div>
                @if($post->category)
                    <span class="text-primary text-[11px] font-bold uppercase tracking-wider">{{ $post->category->name }}</span>
                @endif
                <h3 class="font-bold text-slate-900 font-serif text-lg mt-1 mb-1.5 group-hover:text-primary transition-colors line-clamp-2 leading-snug">{{ $post->title }}</h3>
                <p class="text-slate-500 text-sm line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>
                @if($post->published_at)
                    <p class="text-xs text-slate-400 mt-2">{{ $post->published_at->format('M d, Y') }}</p>
                @endif
            </a>
            @endforeach
        </div>

        <div class="sm:hidden mt-6 text-center">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1 text-primary font-bold text-sm hover:underline">
                All articles <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════════
     NEWSLETTER CTA
═══════════════════════════════════════════════════════════════ --}}
<div class="max-w-[1280px] mx-auto px-4">
    <x-newsletter-cta />
</div>

{{-- ═══════════════════════════════════════════════════════════════
     FOOTER CTA — List your business
═══════════════════════════════════════════════════════════════ --}}
<section class="py-14 sm:py-20 bg-white border-t border-slate-100">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary mb-5">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings:'FILL' 1">add_business</span>
        </div>
        <h2 class="text-2xl sm:text-4xl font-serif font-bold text-slate-900 mb-4">Own a piece of paradise?</h2>
        <p class="text-sm sm:text-lg text-slate-600 mb-8 max-w-xl mx-auto leading-relaxed">
            Join our exclusive network of premium stays, restaurants, and real estate listings. Reach thousands of travellers and buyers — for free.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('register') }}"
               class="bg-primary hover:bg-primary/90 text-white px-8 py-3.5 rounded-full font-bold transition-all shadow-xl shadow-primary/25 w-full sm:w-auto text-center text-sm sm:text-base">
                List Your Business — Free
            </a>
            <a href="{{ route('page.about') }}"
               class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-8 py-3.5 rounded-full font-bold transition-all w-full sm:w-auto text-center text-sm sm:text-base">
                Learn More
            </a>
        </div>
        <p class="text-xs text-slate-400 mt-4">No credit card. No commission. Always free to list.</p>
    </div>
</section>

@endsection

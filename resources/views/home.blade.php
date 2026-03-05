@extends('layouts.app')
@section('title', 'Premium Local Marketplace — Stays, Dining & Real Estate')

@section('content')
{{-- Hero Section --}}
<div class="relative w-full h-[85vh] min-h-[480px] max-h-[700px] sm:h-[600px] sm:max-h-[700px]">
    <div class="absolute inset-0 w-full h-full">
        <img src="{{ asset('images/hello-alibaug-banner.png') }}" alt="Hello Alibaug" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/20 to-black/70"></div>
    </div>

    <div class="relative z-10 h-full max-w-[1280px] mx-auto px-4 sm:px-6 flex flex-col items-center justify-center text-center">
        <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-3 sm:mb-4 drop-shadow-lg leading-tight">
            Experience Coastal Luxury
        </h1>
        <p class="text-sm sm:text-lg text-slate-100 max-w-2xl mb-6 sm:mb-10 drop-shadow-sm font-light px-2">
            Discover curated stays, premium real estate, and authentic local experiences in Alibaug.
        </p>

        {{-- Floating Search Card --}}
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden" x-data="{ activeTab: '{{ $categories->first()?->slug ?? 'stay' }}' }">
            {{-- Category Tabs - Horizontal Scroll on Mobile --}}
            <div class="flex overflow-x-auto hide-scrollbar border-b border-slate-100">
                @foreach($categories as $cat)
                    <button @click="activeTab = '{{ $cat->slug }}'"
                        :class="activeTab === '{{ $cat->slug }}' ? 'text-primary border-b-2 border-primary bg-primary/5 font-bold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
                        class="flex-shrink-0 sm:flex-1 py-3 sm:py-4 px-4 sm:px-6 text-xs sm:text-sm whitespace-nowrap transition-colors">
                        <span class="material-symbols-outlined align-middle mr-0.5 sm:mr-1 text-[16px] sm:text-[20px]">{{ $cat->icon }}</span>
                        <span class="hidden sm:inline">{{ $cat->name }}</span>
                        <span class="sm:hidden">{{ Str::limit($cat->name, 6) }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Search Inputs (Minimalistic) --}}
            <form action="{{ route('search') }}" method="GET" class="p-3 sm:p-5">
                <input type="hidden" name="category" x-bind:value="activeTab">
                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Location Input --}}
                    <div class="flex-grow relative border border-slate-200 rounded-xl bg-slate-50 hover:bg-white focus-within:bg-white focus-within:border-primary/30 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider absolute top-1.5 left-11">Where to?</label>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary/70 text-[22px]">location_on</span>
                        <input type="text" name="q" class="w-full pl-11 pr-4 pt-5 pb-2 bg-transparent border-none text-slate-900 placeholder-slate-400 text-sm sm:text-base font-medium focus:ring-0" placeholder="Search Alibaug...">
                    </div>
                    
                    {{-- Minimalist Guest Count (Optional) --}}
                    <div class="sm:w-48 relative border border-slate-200 rounded-xl bg-slate-50 hover:bg-white focus-within:bg-white focus-within:border-primary/30 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider absolute top-1.5 left-11">Guests</label>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary/70 text-[22px]">group</span>
                        <input type="number" name="guests" min="1" max="50" class="w-full pl-11 pr-4 pt-5 pb-2 bg-transparent border-none text-slate-900 placeholder-slate-400 text-sm sm:text-base font-medium focus:ring-0" placeholder="Any">
                    </div>

                    {{-- Search Action --}}
                    <button type="submit" class="sm:w-auto bg-primary hover:bg-primary/90 text-white rounded-xl px-8 py-3.5 font-bold text-base shadow-lg shadow-primary/30 flex items-center justify-center gap-2 transition-all">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Trust Indicators --}}
<section class="bg-white border-b border-slate-100 py-4 sm:py-6">
    <div class="max-w-[1280px] mx-auto px-4 flex flex-wrap justify-center gap-4 sm:gap-8 md:gap-16 text-slate-600">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl sm:text-2xl">verified</span>
            <span class="font-medium text-xs sm:text-sm">Verified Listings</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl sm:text-2xl">diversity_3</span>
            <span class="font-medium text-xs sm:text-sm">Local Experts</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl sm:text-2xl">support_agent</span>
            <span class="font-medium text-xs sm:text-sm">24/7 Support</span>
        </div>
    </div>
</section>

{{-- Quick Categories --}}
<section class="py-10 sm:py-16 max-w-[1280px] mx-auto px-4">
    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <h2 class="text-xl sm:text-2xl font-serif font-bold text-slate-900">Explore Alibaug</h2>
        <a href="{{ route('search') }}" class="text-primary font-bold text-xs sm:text-sm hover:underline flex items-center">View All <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span></a>
    </div>
    @php
        $catColors = [
            'stay' => ['bg-blue-50', 'text-blue-600', 'from-blue-500/5', 'to-blue-500/0'],
            'eat' => ['bg-rose-50', 'text-rose-500', 'from-rose-500/5', 'to-rose-500/0'],
            'events' => ['bg-purple-50', 'text-purple-500', 'from-purple-500/5', 'to-purple-500/0'],
            'explore' => ['bg-green-50', 'text-green-600', 'from-green-500/5', 'to-green-500/0'],
            'services' => ['bg-teal-50', 'text-teal-500', 'from-teal-500/5', 'to-teal-500/0'],
            'real-estate' => ['bg-orange-50', 'text-orange-500', 'from-orange-500/5', 'to-orange-500/0'],
        ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
        @foreach($categories as $cat)
            @php $colors = $catColors[$cat->slug] ?? ['bg-blue-50', 'text-primary', 'from-blue-500/5', 'to-blue-500/0']; @endphp
            <a href="{{ route('category.show', $cat) }}" class="group relative flex flex-col items-center justify-center p-6 sm:p-8 rounded-3xl bg-white border border-slate-100/80 shadow-[0_2px_20px_rgb(0,0,0,0.02)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b {{ $colors[2] }} {{ $colors[3] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10 w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center mb-4 {{ $colors[0] }} {{ $colors[1] }} group-hover:scale-110 group-hover:bg-white group-hover:shadow-sm transition-all duration-300">
                    <span class="material-symbols-outlined text-3xl sm:text-[32px] font-light" style="font-variation-settings:'FILL' 1">{{ $cat->icon }}</span>
                </div>
                <span class="relative z-10 font-bold text-sm sm:text-base text-slate-800 tracking-tight">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Trending Collections --}}
<section class="py-10 sm:py-12 bg-sand/30">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="mb-6 sm:mb-10">
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mb-1 sm:mb-2">Trending in Alibaug</h2>
            <p class="text-slate-500 text-sm sm:text-base">Curated collections for your next getaway.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="group relative h-60 sm:h-80 rounded-2xl overflow-hidden cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOZ2brO-jZtWb3iRdGBM5rCiF6jK1la5ymlWDbUsrTWPSvHJVnq7Y4LfvPb3iHNR5yeOxhwB6-uVaTeu-QvX8I9kuA2KGhTTzvEbfD7JT_RW8fDdSMiQDKtJ2fdBUStaKxnKNCh6dDm5ZJeTwNsl44ODWBSsJf1bmfc2td836F9EALToeMmwzCF7M0VUKfF7wr3xiVfnlwRsHeZ-U7QAhflbd96DiL9WTs_jt8wewZIaRn-JK7t4GpwCWmC86KAoNoiZ_bGdqbsAk" alt="Beachfront paradise" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 sm:p-6">
                    <h3 class="text-white text-lg sm:text-xl font-bold font-serif mb-1">Beachfront Paradises</h3>
                    <p class="text-white/80 text-xs sm:text-sm mb-2 sm:mb-3">{{ \App\Models\Listing::approved()->count() }} Properties</p>
                    <span class="inline-flex items-center text-white text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-2.5 sm:px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
            <div class="group relative h-60 sm:h-80 rounded-2xl overflow-hidden cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDx08kepwYrJt4uVJ_Q8iEvNnIHB3C860Xs2vP8G8WVTVtG1Rp0Y8FaN8ragk0xzpSRGH0NHW8U7U0Pjiy-77tarRD9LHzrGTmaytx9rK9dyUNJp6VI0dwlKpVqnXv0dvkY1Kl9TJTDIPq0WHMVFfxMJ0lq3CGL92JtUx3udO1d5LST62iSLNVwXP8nX5LrIuIE6JYWC4yVUsfsbVIVGOEI67oSJxnfEIFDKuSW6R5SrtErF7nuTaMitbswqWuBdxPowwlatP228R8" alt="Architectural marvels" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 sm:p-6">
                    <h3 class="text-white text-lg sm:text-xl font-bold font-serif mb-1">Architectural Marvels</h3>
                    <p class="text-white/80 text-xs sm:text-sm mb-2 sm:mb-3">12 Properties</p>
                    <span class="inline-flex items-center text-white text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-2.5 sm:px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
            <div class="group relative h-60 sm:h-80 rounded-2xl overflow-hidden cursor-pointer sm:col-span-2 lg:col-span-1">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAK5GNqgEIYjzy5vRPboUQdLDNdlXyr6eVIUe-PVuHr4CP1GBpvxZyo7tC1UbiRQm4JVwr-W7xu2urrmdjTBl4wV-eRlD_LVvN8JlDNAlLMcF17ICIinG93kThNN4YVqYa4MSOikHMZWKTos6dI_CLiSNncuRpYDM3oQ8mONKLhli-TjCP25x_NcaDldbpSnQ79HR0dLbmzOU464DywNdIZC0E_jiMsPZaET42WyPi8rb330VfAELP70L13OD4ZOkB_Hieq_sqfafc" alt="Luxury resorts" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4 sm:p-6">
                    <h3 class="text-white text-lg sm:text-xl font-bold font-serif mb-1">Luxury Resorts</h3>
                    <p class="text-white/80 text-xs sm:text-sm mb-2 sm:mb-3">8 Properties</p>
                    <span class="inline-flex items-center text-white text-[10px] sm:text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-2.5 sm:px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Listings --}}
<section class="py-10 sm:py-16 max-w-[1280px] mx-auto px-4">
    <div class="flex items-end justify-between mb-6 sm:mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mb-1 sm:mb-2">Featured Stays</h2>
            <p class="text-slate-500 text-sm sm:text-base">Highly rated homes for your perfect vacation.</p>
        </div>
        <a href="{{ route('category.show', 'stay') }}" class="hidden sm:flex text-primary font-bold text-sm hover:underline items-center">View all stays <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span></a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach($featuredListings as $listing)
            @include('components.listing-card', ['listing' => $listing])
        @endforeach
    </div>
    <a href="{{ route('category.show', 'stay') }}" class="sm:hidden flex items-center justify-center gap-1 text-primary font-bold text-sm mt-6 hover:underline">View all stays <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
</section>

{{-- Real Estate Spotlight (Dark Section) --}}
<section class="py-12 sm:py-20 bg-charcoal text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full opacity-10 pointer-events-none hidden sm:block">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.1,-19.2,95.8,-5.1C93.5,9,82.2,22.4,70.9,33.6C59.6,44.8,48.3,53.8,36.4,62.8C24.5,71.8,12,80.8,-0.2,81.1C-12.3,81.4,-24.9,73,-36.4,63.7C-47.9,54.4,-58.3,44.2,-67.6,31.7C-76.9,19.2,-85.1,4.4,-82.9,-9.1C-80.7,-22.6,-68.1,-34.8,-55.8,-43.8C-43.5,-52.8,-31.5,-58.6,-19.4,-67.2C-7.3,-75.8,4.9,-87.2,16.5,-86.6C28.1,-86,30.5,-69.6,44.7,-76.4Z" fill="#FFFFFF" transform="translate(100 100)"></path>
        </svg>
    </div>
    <div class="max-w-[1280px] mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-8 sm:gap-12">
            <div class="lg:w-1/2">
                <span class="text-primary font-bold uppercase tracking-widest text-xs sm:text-sm mb-2 block">Real Estate Spotlight</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold mb-4 sm:mb-6 leading-tight">Find Your Coastal Dream Home</h2>
                <p class="text-slate-300 text-sm sm:text-lg mb-6 sm:mb-8 leading-relaxed">
                    Exclusive access to Alibaug's most coveted land parcels and luxury villas. Whether you're looking for a vacation home or an investment, our experts guide you every step of the way.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('category.show', 'real-estate') }}" class="bg-primary hover:bg-primary/90 text-white px-6 sm:px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-primary/20 text-center text-sm sm:text-base">Browse Properties</a>
                    <a href="#" class="bg-transparent border border-slate-500 hover:border-white text-white px-6 sm:px-8 py-3 rounded-xl font-bold transition-all text-center text-sm sm:text-base">Talk to an Agent</a>
                </div>
                <div class="mt-8 sm:mt-10 flex gap-6 sm:gap-8">
                    <div>
                        <p class="text-2xl sm:text-3xl font-serif font-bold">150+</p>
                        <p class="text-slate-400 text-xs sm:text-sm">Sold Properties</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-serif font-bold">₹50Cr+</p>
                        <p class="text-slate-400 text-xs sm:text-sm">Value Transacted</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 w-full">
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoq5HSGj_rGnnQYNWExlk-xpJUoKlh3Tmelol2EetYt_1sDRKSajXt5Lz_jBTL1CPg3HIvDLucc8NZLRyuDMBydhyQjpndFIulabcVxhT_1BckU8fisVquejmuRN1SmFmRZhyM-HJxCwT-L3VbJ3BJbpnyu_n7HCLTghJ1hzzsAO7ZU7MUum58W524kxv1S3pkTTNzdmZuY33E2GNII8hE5UIi8vx9g0ehCrnyhTIS6XNzVcJqquC59PmqvxaYSCo8Uh64xMJlgY4" alt="Modern architecture" class="rounded-2xl w-full h-40 sm:h-64 object-cover translate-y-4 sm:translate-y-8 shadow-2xl" loading="lazy">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6oKhFyhtGU4Waqi_5VtRv7UZtByJtlXVJhbHxYsGsVc_xSfWX9sbW6Sr9sFFAcL7bSyIdyCZyjsFd2kn25TV18YqHoSgeWPzUj_hP24A_-Ao13P1oA5CBEaGSNOMGuL8mZoA9u3IH-uyn98v74sezhlcxVLfrH29A1c89rAbW6Ou-hZVCjRtb5H3azylEBHc5XFOBZK4uC9Ng166Dml2mCzCK5_UGh6AyeKZAh3DF16ev1lb7HgGmkQVt2T7qu6iO0y2y70p8QFE" alt="Bright interior" class="rounded-2xl w-full h-40 sm:h-64 object-cover shadow-2xl" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Explore by Area --}}
<section class="py-10 sm:py-16 bg-white">
    <div class="max-w-[1280px] mx-auto px-4">
        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mb-6 sm:mb-8 text-center">Explore by Area</h2>
        @php
            $areaImages = [
                'Mandwa' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=2070&auto=format&fit=crop',
                'Alibag' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop',
                'Alibaug' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?q=80&w=2075&auto=format&fit=crop',
                'Alibaug Town' => 'https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop',
                'Awas' => 'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?q=80&w=2070&auto=format&fit=crop',
                'Kihim' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2073&auto=format&fit=crop',
                'Nagaon' => 'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=2070&auto=format&fit=crop',
                'Varsoli' => 'https://images.unsplash.com/photo-1596178065887-f273200ff960?q=80&w=2070&auto=format&fit=crop',
                'Thal' => 'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?q=80&w=2070&auto=format&fit=crop',
            ];
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6 auto-rows-[200px] sm:auto-rows-[300px]">
            @foreach($areas->take(4) as $idx => $area)
                @php $fallbackImg = $areaImages[$area->name] ?? 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?q=80&w=2062&auto=format&fit=crop'; @endphp
                <div class="relative rounded-xl sm:rounded-2xl overflow-hidden group {{ $idx === 0 || $idx === 3 ? 'md:col-span-2' : '' }} cursor-pointer">
                    @if($area->image)
                        <img src="{{ $area->image }}" alt="{{ $area->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    @else
                        <img src="{{ $fallbackImg }}" alt="{{ $area->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-3 left-3 sm:bottom-6 sm:left-6 text-white">
                        <h3 class="text-lg sm:text-2xl font-bold font-serif">{{ $area->name }}</h3>
                        <p class="text-xs sm:text-sm opacity-90 hidden sm:block">{{ $area->tagline }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stories from the Coast --}}
<section class="py-10 sm:py-16 bg-sand/20">
    <div class="max-w-[1280px] mx-auto px-4">
        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mb-6 sm:mb-8">Stories from the Coast</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-8">
            <article class="flex flex-row sm:flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl w-28 h-28 sm:w-full sm:h-56 flex-shrink-0">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuATb9RqGRLNFwUnQkWGl-9So1gvmChn9lIU5-O8ax4n6CwjvO5_9BVjLpG7-OWLHtD3WzXUsUMn6WrUw8jgAKWUhGwW63c_ctTOT-HiYF5llGQd7rhuC0VPDEO59P22jRNnr96kagM51UHylZHGsWreEzgPzIudqafKUyGFAPmk1tfzLRtxdBYXHSv5MERRU2kkgbbffH7CYp0EOiDP_9x_LKgbKY48QuSWD2PNxaYksGkz9N92V3K3Cz7PhEIAqibMH57qGdTKPEc" alt="5 Hidden Cafes" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-1 sm:gap-2 justify-center">
                    <span class="text-primary text-[10px] sm:text-xs font-bold uppercase tracking-wider">Eat & Drink</span>
                    <h3 class="text-base sm:text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors line-clamp-2">5 Hidden Cafes in Alibaug You Must Visit</h3>
                    <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 hidden sm:block">Discover the culinary secrets tucked away in the lanes of Alibaug.</p>
                </div>
            </article>
            <article class="flex flex-row sm:flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl w-28 h-28 sm:w-full sm:h-56 flex-shrink-0">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrHIBIjXX_Bd5taVuGlHHl5OWj7SUpm5UA0_SpLyUZFBT7cKS2X0jA795eoEDA03YttzVCUKMWGwxZm8G2e3PcThLHKVz_nbBRVR3kvpWPrjkuEFoQbifA-1onB1yLKTG7i_I01NPF22ex_NBg4gya7KYbJ5b5VF1Hvd-t1MMUKwpUxrBOKFslCJUjbinbCcxqlMrD1Hq1AvdBvuuRQy0fi7dabCTjh2T6c-4arVyPshG4AD1reNzWAFbxV3bnji10MzAMu-w0nu8" alt="Investing in Alibaug" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-1 sm:gap-2 justify-center">
                    <span class="text-primary text-[10px] sm:text-xs font-bold uppercase tracking-wider">Real Estate</span>
                    <h3 class="text-base sm:text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors line-clamp-2">Investing in Alibaug: 2024 Market Outlook</h3>
                    <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 hidden sm:block">Why Alibaug continues to be the top choice for second home buyers.</p>
                </div>
            </article>
            <article class="flex flex-row sm:flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl w-28 h-28 sm:w-full sm:h-56 flex-shrink-0">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSKS4HcvLlxOIaLk0ablOzy76JE9Mu3upGe1NogtzvkpA8Ww81gcVCDd1AFnjPZxN86gVsqQegf-dxLSkDgpHlI_QNdC09Y3g9SQtx0oL6jkgPcGVVACwipyFgEw3JiXRrJSxGuGWB_Qq4xI_7w_XJwKUL5pVjwlzl2GI3DQApnAiX3bOYy5SaF9Ev0-DO42vZ5TdhPnql-jFR5fhXHe705iTmBWm58GUel3ZCXBFK0djNqo8mgKdNG9KV54LH3LMa_2bghbfjs0Y" alt="Weekend Itinerary" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-1 sm:gap-2 justify-center">
                    <span class="text-primary text-[10px] sm:text-xs font-bold uppercase tracking-wider">Lifestyle</span>
                    <h3 class="text-base sm:text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors line-clamp-2">A Weekend Itinerary for Wellness Lovers</h3>
                    <p class="text-slate-500 text-xs sm:text-sm line-clamp-2 hidden sm:block">Yoga by the beach, organic farm visits, and spa retreats.</p>
                </div>
            </article>
        </div>
    </div>
</section>

{{-- Footer CTA --}}
<section class="py-12 sm:py-20 bg-white border-t border-slate-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-4xl font-serif font-bold text-slate-900 mb-4 sm:mb-6">Own a piece of paradise?</h2>
        <p class="text-sm sm:text-lg text-slate-600 mb-6 sm:mb-8 max-w-2xl mx-auto">
            Join our exclusive network of premium stays and real estate. List your property with Hello Alibaug and reach discerning travelers and buyers.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
            <a href="{{ route('register') }}" class="bg-primary hover:bg-primary/90 text-white px-6 sm:px-8 py-3 sm:py-3.5 rounded-full text-sm sm:text-base font-bold transition-all shadow-xl shadow-primary/25 w-full sm:w-auto text-center">
                List Your Business
            </a>
            <a href="#" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-6 sm:px-8 py-3 sm:py-3.5 rounded-full text-sm sm:text-base font-bold transition-all w-full sm:w-auto text-center">
                Learn More
            </a>
        </div>
    </div>
</section>
@endsection

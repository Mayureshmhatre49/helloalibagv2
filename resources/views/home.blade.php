@extends('layouts.app')
@section('title', 'Premium Local Marketplace — Stays, Dining & Real Estate')

@section('content')
{{-- Hero Section --}}
<div class="relative w-full h-[600px] sm:h-[700px]">
    <div class="absolute inset-0 w-full h-full">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDCjXJ9SdsU7gHAoMBh-JX1MWxPHHkE3WrHJRSbazkXoRB7JU6ktFz0SEFYSQDEpdPwmP4wuTxuHmxRIPLXekcAGVML9IH3fFdaq8Ap2Q0nh9G_PmOSstoRAAo4N6LClAMQVX-X4n6r19vZWKy4nsuSH3wcAVJ5QZ8bLHvq50lCfZcYnkytR9wkq-3JN8ld2hJAaA1jAwNOoFMx0ttBb83vl4Tsm8GDKyswgf1iI55Uvou1CSNfTxLvm3PrLufWPXg1I1-KutRovQ" alt="Luxury coastal villa" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/20 to-black/60"></div>
    </div>

    <div class="relative z-10 h-full max-w-[1280px] mx-auto px-4 flex flex-col items-center justify-center text-center">
        <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-4 drop-shadow-md">
            Experience Coastal Luxury
        </h1>
        <p class="text-lg text-slate-100 max-w-2xl mb-10 drop-shadow-sm font-light">
            Discover curated stays, premium real estate, and authentic local experiences in Alibaug.
        </p>

        {{-- Floating Search Card --}}
        <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden" x-data="{ activeTab: '{{ $categories->first()?->slug ?? 'stay' }}' }">
            {{-- Category Tabs --}}
            <div class="flex overflow-x-auto hide-scrollbar border-b border-slate-100">
                @foreach($categories as $cat)
                    <button @click="activeTab = '{{ $cat->slug }}'"
                        :class="activeTab === '{{ $cat->slug }}' ? 'text-primary border-b-2 border-primary bg-primary/5 font-bold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium'"
                        class="flex-1 py-4 px-6 text-sm whitespace-nowrap transition-colors">
                        <span class="material-symbols-outlined align-middle mr-1 text-[20px]">{{ $cat->icon }}</span>
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            {{-- Search Inputs --}}
            <form action="{{ route('search') }}" method="GET" class="p-4 sm:p-6 flex flex-col sm:flex-row gap-4">
                <input type="hidden" name="category" x-bind:value="activeTab">
                <div class="flex-1 relative">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 pl-1">Location</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">location_on</span>
                        <input type="text" name="q" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 text-base font-medium" placeholder="Where in Alibaug?">
                    </div>
                </div>
                <div class="flex-1 relative border-l-0 sm:border-l border-slate-200 sm:pl-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 pl-1">Dates</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">calendar_today</span>
                        <input type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 text-base font-medium" placeholder="Add dates">
                    </div>
                </div>
                <div class="flex-1 relative border-l-0 sm:border-l border-slate-200 sm:pl-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 pl-1">Guests</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">group</span>
                        <input type="text" class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-primary/50 text-base font-medium" placeholder="Add guests">
                    </div>
                </div>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-white rounded-xl px-8 py-3 font-bold text-lg shadow-lg shadow-primary/30 flex items-center justify-center gap-2 transition-all mt-4 sm:mt-auto">
                    <span class="material-symbols-outlined">search</span>
                    Search
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Trust Indicators --}}
<section class="bg-white border-b border-slate-100 py-6">
    <div class="max-w-[1280px] mx-auto px-4 flex flex-wrap justify-center gap-8 sm:gap-16 text-slate-600">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-2xl">verified</span>
            <span class="font-medium text-sm">Verified Premium Listings</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-2xl">diversity_3</span>
            <span class="font-medium text-sm">Local Expert Guidance</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-2xl">support_agent</span>
            <span class="font-medium text-sm">24/7 Concierge Support</span>
        </div>
    </div>
</section>

{{-- Quick Categories --}}
<section class="py-16 max-w-[1280px] mx-auto px-4">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-serif font-bold text-slate-900">Explore Alibaug</h2>
        <a href="{{ route('search') }}" class="text-primary font-bold text-sm hover:underline flex items-center">View All <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span></a>
    </div>
    @php
        $catColors = [
            'stay' => ['bg-blue-50', 'text-primary'],
            'eat' => ['bg-rose-50', 'text-rose-500'],
            'events' => ['bg-purple-50', 'text-purple-500'],
            'explore' => ['bg-green-50', 'text-green-600'],
            'services' => ['bg-teal-50', 'text-teal-500'],
            'real-estate' => ['bg-orange-50', 'text-orange-500'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
        @foreach($categories as $cat)
            @php $colors = $catColors[$cat->slug] ?? ['bg-blue-50', 'text-primary']; @endphp
            <a href="{{ route('category.show', $cat) }}" class="group flex flex-col items-center gap-3 p-4 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 hover:shadow-lg transition-all">
                <div class="p-3 rounded-full {{ $colors[0] }} {{ $colors[1] }} group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">{{ $cat->icon }}</span>
                </div>
                <span class="font-bold text-sm text-slate-700">{{ $cat->name }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Trending Collections --}}
<section class="py-12 bg-sand/30">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="mb-10">
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2">Trending in Alibaug</h2>
            <p class="text-slate-500">Curated collections for your next getaway.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="group relative h-80 rounded-2xl overflow-hidden cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOZ2brO-jZtWb3iRdGBM5rCiF6jK1la5ymlWDbUsrTWPSvHJVnq7Y4LfvPb3iHNR5yeOxhwB6-uVaTeu-QvX8I9kuA2KGhTTzvEbfD7JT_RW8fDdSMiQDKtJ2fdBUStaKxnKNCh6dDm5ZJeTwNsl44ODWBSsJf1bmfc2td836F9EALToeMmwzCF7M0VUKfF7wr3xiVfnlwRsHeZ-U7QAhflbd96DiL9WTs_jt8wewZIaRn-JK7t4GpwCWmC86KAoNoiZ_bGdqbsAk" alt="Beachfront paradise" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-white text-xl font-bold font-serif mb-1">Beachfront Paradises</h3>
                    <p class="text-white/80 text-sm mb-3">{{ \App\Models\Listing::approved()->count() }} Properties</p>
                    <span class="inline-flex items-center text-white text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
            <div class="group relative h-80 rounded-2xl overflow-hidden cursor-pointer">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDx08kepwYrJt4uVJ_Q8iEvNnIHB3C860Xs2vP8G8WVTVtG1Rp0Y8FaN8ragk0xzpSRGH0NHW8U7U0Pjiy-77tarRD9LHzrGTmaytx9rK9dyUNJp6VI0dwlKpVqnXv0dvkY1Kl9TJTDIPq0WHMVFfxMJ0lq3CGL92JtUx3udO1d5LST62iSLNVwXP8nX5LrIuIE6JYWC4yVUsfsbVIVGOEI67oSJxnfEIFDKuSW6R5SrtErF7nuTaMitbswqWuBdxPowwlatP228R8" alt="Architectural marvels" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-white text-xl font-bold font-serif mb-1">Architectural Marvels</h3>
                    <p class="text-white/80 text-sm mb-3">12 Properties</p>
                    <span class="inline-flex items-center text-white text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
            <div class="group relative h-80 rounded-2xl overflow-hidden cursor-pointer md:col-span-2 lg:col-span-1">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAK5GNqgEIYjzy5vRPboUQdLDNdlXyr6eVIUe-PVuHr4CP1GBpvxZyo7tC1UbiRQm4JVwr-W7xu2urrmdjTBl4wV-eRlD_LVvN8JlDNAlLMcF17ICIinG93kThNN4YVqYa4MSOikHMZWKTos6dI_CLiSNncuRpYDM3oQ8mONKLhli-TjCP25x_NcaDldbpSnQ79HR0dLbmzOU464DywNdIZC0E_jiMsPZaET42WyPi8rb330VfAELP70L13OD4ZOkB_Hieq_sqfafc" alt="Luxury resorts" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-white text-xl font-bold font-serif mb-1">Luxury Resorts</h3>
                    <p class="text-white/80 text-sm mb-3">8 Properties</p>
                    <span class="inline-flex items-center text-white text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">Explore</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Listings --}}
<section class="py-16 max-w-[1280px] mx-auto px-4">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-3xl font-serif font-bold text-slate-900 mb-2">Featured Stays</h2>
            <p class="text-slate-500">Highly rated homes for your perfect vacation.</p>
        </div>
        <a href="{{ route('category.show', 'stay') }}" class="hidden sm:flex text-primary font-bold text-sm hover:underline items-center">View all stays <span class="material-symbols-outlined text-sm ml-1">arrow_forward</span></a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($featuredListings as $listing)
            @include('components.listing-card', ['listing' => $listing])
        @endforeach
    </div>
</section>

{{-- Real Estate Spotlight (Dark Section) --}}
<section class="py-20 bg-charcoal text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full opacity-10 pointer-events-none">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.1,-19.2,95.8,-5.1C93.5,9,82.2,22.4,70.9,33.6C59.6,44.8,48.3,53.8,36.4,62.8C24.5,71.8,12,80.8,-0.2,81.1C-12.3,81.4,-24.9,73,-36.4,63.7C-47.9,54.4,-58.3,44.2,-67.6,31.7C-76.9,19.2,-85.1,4.4,-82.9,-9.1C-80.7,-22.6,-68.1,-34.8,-55.8,-43.8C-43.5,-52.8,-31.5,-58.6,-19.4,-67.2C-7.3,-75.8,4.9,-87.2,16.5,-86.6C28.1,-86,30.5,-69.6,44.7,-76.4Z" fill="#FFFFFF" transform="translate(100 100)"></path>
        </svg>
    </div>
    <div class="max-w-[1280px] mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2">
                <span class="text-primary font-bold uppercase tracking-widest text-sm mb-2 block">Real Estate Spotlight</span>
                <h2 class="text-4xl md:text-5xl font-serif font-bold mb-6 leading-tight">Find Your Coastal Dream Home</h2>
                <p class="text-slate-300 text-lg mb-8 leading-relaxed">
                    Exclusive access to Alibaug's most coveted land parcels and luxury villas. Whether you're looking for a vacation home or an investment, our experts guide you every step of the way.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('category.show', 'real-estate') }}" class="bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-primary/20 text-center">Browse Properties</a>
                    <a href="#" class="bg-transparent border border-slate-500 hover:border-white text-white px-8 py-3 rounded-xl font-bold transition-all text-center">Talk to an Agent</a>
                </div>
                <div class="mt-10 flex gap-8">
                    <div>
                        <p class="text-3xl font-serif font-bold">150+</p>
                        <p class="text-slate-400 text-sm">Sold Properties</p>
                    </div>
                    <div>
                        <p class="text-3xl font-serif font-bold">₹50Cr+</p>
                        <p class="text-slate-400 text-sm">Value Transacted</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 w-full">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAoq5HSGj_rGnnQYNWExlk-xpJUoKlh3Tmelol2EetYt_1sDRKSajXt5Lz_jBTL1CPg3HIvDLucc8NZLRyuDMBydhyQjpndFIulabcVxhT_1BckU8fisVquejmuRN1SmFmRZhyM-HJxCwT-L3VbJ3BJbpnyu_n7HCLTghJ1hzzsAO7ZU7MUum58W524kxv1S3pkTTNzdmZuY33E2GNII8hE5UIi8vx9g0ehCrnyhTIS6XNzVcJqquC59PmqvxaYSCo8Uh64xMJlgY4" alt="Modern architecture" class="rounded-2xl w-full h-64 object-cover translate-y-8 shadow-2xl" loading="lazy">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuA6oKhFyhtGU4Waqi_5VtRv7UZtByJtlXVJhbHxYsGsVc_xSfWX9sbW6Sr9sFFAcL7bSyIdyCZyjsFd2kn25TV18YqHoSgeWPzUj_hP24A_-Ao13P1oA5CBEaGSNOMGuL8mZoA9u3IH-uyn98v74sezhlcxVLfrH29A1c89rAbW6Ou-hZVCjRtb5H3azylEBHc5XFOBZK4uC9Ng166Dml2mCzCK5_UGh6AyeKZAh3DF16ev1lb7HgGmkQVt2T7qu6iO0y2y70p8QFE" alt="Bright interior" class="rounded-2xl w-full h-64 object-cover shadow-2xl" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Explore by Area --}}
<section class="py-16 bg-white">
    <div class="max-w-[1280px] mx-auto px-4">
        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8 text-center">Explore by Area</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[300px]">
            @foreach($areas->take(4) as $idx => $area)
                <div class="relative rounded-2xl overflow-hidden group {{ $idx === 0 || $idx === 3 ? 'md:col-span-2' : '' }} cursor-pointer">
                    @if($area->image)
                        <img src="{{ $area->image }}" alt="{{ $area->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/60 to-charcoal"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-2xl font-bold font-serif">{{ $area->name }}</h3>
                        <p class="text-sm opacity-90">{{ $area->tagline }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Stories from the Coast --}}
<section class="py-16 bg-sand/20">
    <div class="max-w-[1280px] mx-auto px-4">
        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-8">Stories from the Coast</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <article class="flex flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl h-56">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuATb9RqGRLNFwUnQkWGl-9So1gvmChn9lIU5-O8ax4n6CwjvO5_9BVjLpG7-OWLHtD3WzXUsUMn6WrUw8jgAKWUhGwW63c_ctTOT-HiYF5llGQd7rhuC0VPDEO59P22jRNnr96kagM51UHylZHGsWreEzgPzIudqafKUyGFAPmk1tfzLRtxdBYXHSv5MERRU2kkgbbffH7CYp0EOiDP_9x_LKgbKY48QuSWD2PNxaYksGkz9N92V3K3Cz7PhEIAqibMH57qGdTKPEc" alt="5 Hidden Cafes" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-2">
                    <span class="text-primary text-xs font-bold uppercase tracking-wider">Eat & Drink</span>
                    <h3 class="text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors">5 Hidden Cafes in Alibaug You Must Visit</h3>
                    <p class="text-slate-500 text-sm line-clamp-2">Discover the culinary secrets tucked away in the lanes of Alibaug, from artisanal bakeries to seaside shacks.</p>
                </div>
            </article>
            <article class="flex flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl h-56">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrHIBIjXX_Bd5taVuGlHHl5OWj7SUpm5UA0_SpLyUZFBT7cKS2X0jA795eoEDA03YttzVCUKMWGwxZm8G2e3PcThLHKVz_nbBRVR3kvpWPrjkuEFoQbifA-1onB1yLKTG7i_I01NPF22ex_NBg4gya7KYbJ5b5VF1Hvd-t1MMUKwpUxrBOKFslCJUjbinbCcxqlMrD1Hq1AvdBvuuRQy0fi7dabCTjh2T6c-4arVyPshG4AD1reNzWAFbxV3bnji10MzAMu-w0nu8" alt="Investing in Alibaug" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-2">
                    <span class="text-primary text-xs font-bold uppercase tracking-wider">Real Estate</span>
                    <h3 class="text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors">Investing in Alibaug: 2024 Market Outlook</h3>
                    <p class="text-slate-500 text-sm line-clamp-2">Why Alibaug continues to be the top choice for second home buyers and investors from Mumbai and Pune.</p>
                </div>
            </article>
            <article class="flex flex-col gap-3 group cursor-pointer">
                <div class="overflow-hidden rounded-xl h-56">
                    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDSKS4HcvLlxOIaLk0ablOzy76JE9Mu3upGe1NogtzvkpA8Ww81gcVCDd1AFnjPZxN86gVsqQegf-dxLSkDgpHlI_QNdC09Y3g9SQtx0oL6jkgPcGVVACwipyFgEw3JiXRrJSxGuGWB_Qq4xI_7w_XJwKUL5pVjwlzl2GI3DQApnAiX3bOYy5SaF9Ev0-DO42vZ5TdhPnql-jFR5fhXHe705iTmBWm58GUel3ZCXBFK0djNqo8mgKdNG9KV54LH3LMa_2bghbfjs0Y" alt="Weekend Itinerary" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                </div>
                <div class="flex flex-col gap-2">
                    <span class="text-primary text-xs font-bold uppercase tracking-wider">Lifestyle</span>
                    <h3 class="text-xl font-bold font-serif text-slate-900 group-hover:text-primary transition-colors">A Weekend Itinerary for Wellness Lovers</h3>
                    <p class="text-slate-500 text-sm line-clamp-2">Yoga by the beach, organic farm visits, and spa retreats. Plan your rejuvenating weekend escape.</p>
                </div>
            </article>
        </div>
    </div>
</section>

{{-- Footer CTA --}}
<section class="py-20 bg-white border-t border-slate-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-serif font-bold text-slate-900 mb-6">Own a piece of paradise?</h2>
        <p class="text-lg text-slate-600 mb-8 max-w-2xl mx-auto">
            Join our exclusive network of premium stays and real estate. List your property with Hello Alibaug and reach discerning travelers and buyers.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-primary hover:bg-primary/90 text-white px-8 py-3.5 rounded-full text-base font-bold transition-all shadow-xl shadow-primary/25 w-full sm:w-auto text-center">
                List Your Business
            </a>
            <a href="#" class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-8 py-3.5 rounded-full text-base font-bold transition-all w-full sm:w-auto text-center">
                Learn More
            </a>
        </div>
    </div>
</section>
@endsection

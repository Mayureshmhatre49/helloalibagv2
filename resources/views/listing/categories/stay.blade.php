{{--
    Stay category detail template.
    Partial — no @extends. Included from show.blade.php.
    Available: $listing, $avgRating, $reviewCount, $catSlug, $priceLabel,
               $relatedListings, $recentlyViewed, $dynAttrs.
--}}

@php
    $propType   = $listing->listingAttributes->where('attribute_key', 'property_type')->first()?->attribute_value;
    $bedrooms   = $listing->listingAttributes->where('attribute_key', 'bedrooms')->first()?->attribute_value;
    $bathrooms  = $listing->listingAttributes->where('attribute_key', 'bathrooms')->first()?->attribute_value;
    $maxGuests  = $listing->listingAttributes->where('attribute_key', 'max_guests')->first()?->attribute_value;
    $checkIn    = $listing->listingAttributes->where('attribute_key', 'check_in')->first()?->attribute_value;
    $checkOut   = $listing->listingAttributes->where('attribute_key', 'check_out')->first()?->attribute_value;
    $minStay    = $listing->listingAttributes->where('attribute_key', 'min_stay')->first()?->attribute_value;
    $hasStayDetails = $checkIn || $checkOut || $minStay;
@endphp

<div class="bg-slate-50 pb-24 lg:pb-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    {{-- ── BREADCRUMB ────────────────────────────────────────────────────── --}}
    @include('listing.partials._breadcrumb')

    {{-- ── GALLERY ──────────────────────────────────────────────────────── --}}
    @include('listing.partials._gallery')

    {{-- ── QUICK HIGHLIGHTS STRIP ──────────────────────────────────────── --}}
    @if($propType || $bedrooms || $bathrooms || $maxGuests)
        <div class="flex items-center gap-3 overflow-x-auto scrollbar-none pb-1 mb-5 -mx-1 px-1">
            @if($propType)
                <div class="flex-shrink-0 inline-flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">home</span>
                    <span class="text-sm font-semibold text-slate-700 whitespace-nowrap">{{ $propType }}</span>
                </div>
            @endif
            @if($bedrooms)
                <div class="flex-shrink-0 inline-flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">bed</span>
                    <span class="text-sm font-semibold text-slate-700 whitespace-nowrap">{{ $bedrooms }} {{ Str::plural('Bedroom', (int)$bedrooms) }}</span>
                </div>
            @endif
            @if($bathrooms)
                <div class="flex-shrink-0 inline-flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">shower</span>
                    <span class="text-sm font-semibold text-slate-700 whitespace-nowrap">{{ $bathrooms }} {{ Str::plural('Bathroom', (int)$bathrooms) }}</span>
                </div>
            @endif
            @if($maxGuests)
                <div class="flex-shrink-0 inline-flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-4 py-2.5 shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[20px]">group</span>
                    <span class="text-sm font-semibold text-slate-700 whitespace-nowrap">Up to {{ $maxGuests }} Guests</span>
                </div>
            @endif
        </div>
    @endif

    {{-- ── TITLE + META ─────────────────────────────────────────────────── --}}
    <div class="mb-6">

        {{-- Badges --}}
        @if($listing->is_featured || $listing->is_premium)
            <div class="flex items-center gap-2 mb-3">
                @if($listing->is_featured)
                    <span class="inline-flex items-center gap-1 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">star</span>
                        Featured
                    </span>
                @endif
                @if($listing->is_premium)
                    <span class="inline-flex items-center gap-1 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">workspace_premium</span>
                        Premium
                    </span>
                @endif
            </div>
        @endif

        {{-- Title --}}
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 leading-tight mb-3">
            {{ $listing->title }}
        </h1>

        {{-- Rating + Location + Views --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-sm mb-4">
            @if($avgRating > 0)
                <span class="flex items-center gap-1.5 font-bold text-slate-900">
                    <span class="material-symbols-outlined text-amber-400 text-[18px]" style="font-variation-settings:'FILL' 1">star</span>
                    {{ $avgRating }}
                    <span class="font-normal text-slate-500">· {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full text-xs font-medium">New</span>
            @endif

            <span class="flex items-center gap-1 text-slate-600">
                <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                {{ $listing->area?->name ? $listing->area->name . ', Alibaug' : 'Alibaug' }}
            </span>

            <span class="flex items-center gap-1 text-slate-400 text-xs">
                <span class="material-symbols-outlined text-[14px]">visibility</span>
                {{ number_format($listing->views_count) }} views
            </span>
        </div>

        {{-- Tags --}}
        @if($listing->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($listing->tags as $tag)
                    <span class="inline-flex items-center gap-1.5 bg-primary/5 border border-primary/20 text-primary px-3 py-1 rounded-full text-xs font-semibold">
                        @if($tag->icon)
                            <span class="material-symbols-outlined text-[13px]">{{ $tag->icon }}</span>
                        @endif
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Share buttons --}}
        <div class="flex items-center gap-2 pt-4 border-t border-slate-100" x-data="{ copied: false }">
            <span class="text-xs text-slate-400 font-medium mr-1">Share:</span>
            <a href="https://wa.me/?text={{ urlencode($listing->title . ' — ' . url()->current()) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-[#25D366] bg-[#25D366]/10 hover:bg-[#25D366]/20 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]">chat</span> WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 bg-blue-500/10 hover:bg-blue-500/20 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]">share</span> Facebook
            </a>
            <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]" x-text="copied ? 'check' : 'link'">link</span>
                <span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
            </button>
        </div>
    </div>

    {{-- ── TWO-COLUMN LAYOUT ────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ── MAIN CONTENT ─────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- 1. About --}}
            @include('listing.partials._about', ['descHeading' => 'About the property'])

            {{-- 2. Stay Details card --}}
            @if($hasStayDetails)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">hotel</span>
                        Stay Details
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @if($checkIn)
                            <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5">login</span>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Check-in</p>
                                    <p class="text-base font-bold text-slate-800">
                                        @php
                                            try { echo \Carbon\Carbon::createFromFormat('H:i', $checkIn)->format('g:i A'); }
                                            catch (\Exception $e) { echo $checkIn; }
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($checkOut)
                            <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5">logout</span>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Check-out</p>
                                    <p class="text-base font-bold text-slate-800">
                                        @php
                                            try { echo \Carbon\Carbon::createFromFormat('H:i', $checkOut)->format('g:i A'); }
                                            catch (\Exception $e) { echo $checkOut; }
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        @endif
                        @if($minStay)
                            <div class="flex items-start gap-3 bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0 mt-0.5">calendar_month</span>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Minimum Stay</p>
                                    <p class="text-base font-bold text-slate-800">{{ $minStay }} {{ Str::plural('night', (int)$minStay) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 3. Amenities --}}
            @include('listing.partials._amenities', ['amenitiesHeading' => "What's included"])

            {{-- 4. Map --}}
            @include('listing.partials._map')

            {{-- 5. Reviews --}}
            @include('listing.partials._reviews')

        </div>
        {{-- /main content --}}

        {{-- ── SIDEBAR ──────────────────────────────────────────────────── --}}
        @include('listing.partials._sidebar', [
            'sidebarCtaLabel'    => 'Enquire to Book',
            'sidebarShowDates'   => true,
            'sidebarDateLabel'   => 'Check-in',
            'sidebarDate2Label'  => 'Check-out',
            'sidebarShowGuests'  => true,
            'sidebarGuestsLabel' => 'Number of guests',
        ])

    </div>
    {{-- /two-column layout --}}

    {{-- ── RELATED + RECENTLY VIEWED ───────────────────────────────────── --}}
    @include('listing.partials._related')

</div>
</div>

{{-- ── MOBILE STICKY BAR ────────────────────────────────────────────── --}}
@include('listing.partials._mobile-bar', ['mobileCta' => 'Book Now'])

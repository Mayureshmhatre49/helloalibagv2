{{--
  Category partial: Explore / Activities
  Included from listing/show.blade.php
  Variables available: $listing, $avgRating, $reviewCount, $catSlug, $priceLabel,
                       $relatedListings, $recentlyViewed, $dynAttrs
--}}

@php
    $activityType = $listing->listingAttributes->where('attribute_key', 'activity_type')->first()?->attribute_value;
    $duration     = $listing->listingAttributes->where('attribute_key', 'duration')->first()?->attribute_value;
    $difficulty   = $listing->listingAttributes->where('attribute_key', 'difficulty')->first()?->attribute_value;
    $groupSize    = $listing->listingAttributes->where('attribute_key', 'group_size')->first()?->attribute_value;

    $difficultyClasses = match(strtolower($difficulty ?? '')) {
        'easy'     => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'border' => 'border-green-200'],
        'moderate' => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'border' => 'border-amber-200'],
        'hard'     => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'border' => 'border-red-200'],
        default    => ['bg' => 'bg-slate-100',  'text' => 'text-slate-700',  'border' => 'border-slate-200'],
    };
@endphp

<div class="bg-slate-50 pb-24 lg:pb-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    {{-- ── BREADCRUMB ──────────────────────────────────────────────────────── --}}
    @include('listing.partials._breadcrumb')

    {{-- ── GALLERY ──────────────────────────────────────────────────────────── --}}
    @include('listing.partials._gallery')

    {{-- ── ACTIVITY HIGHLIGHTS STRIP ───────────────────────────────────────── --}}
    @if($activityType || $duration || $difficulty || $groupSize)
        <div class="mb-6 flex flex-wrap gap-2.5">
            @if($activityType)
                <span class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-full text-sm font-medium shadow-sm">
                    <span class="material-symbols-outlined text-[17px] text-primary">hiking</span>
                    {{ $activityType }}
                </span>
            @endif
            @if($duration)
                <span class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-full text-sm font-medium shadow-sm">
                    <span class="material-symbols-outlined text-[17px] text-primary">schedule</span>
                    {{ $duration }}
                </span>
            @endif
            @if($difficulty)
                <span class="inline-flex items-center gap-2 border px-4 py-2 rounded-full text-sm font-medium shadow-sm
                             {{ $difficultyClasses['bg'] }} {{ $difficultyClasses['text'] }} {{ $difficultyClasses['border'] }}">
                    <span class="material-symbols-outlined text-[17px]">signal_cellular_alt</span>
                    {{ ucfirst($difficulty) }}
                </span>
            @endif
            @if($groupSize)
                <span class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-full text-sm font-medium shadow-sm">
                    <span class="material-symbols-outlined text-[17px] text-primary">group</span>
                    {{ $groupSize }}
                </span>
            @endif
        </div>
    @endif

    {{-- ── TITLE + META ─────────────────────────────────────────────────────── --}}
    <div class="mb-6">
        {{-- Badges row --}}
        @if($listing->is_featured || $listing->is_premium)
            <div class="flex items-center gap-2 mb-3">
                @if($listing->is_featured)
                    <span class="inline-flex items-center gap-1 bg-primary text-white px-3 py-1 rounded-full text-xs font-bold">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">star</span> Featured
                    </span>
                @elseif($listing->is_premium)
                    <span class="inline-flex items-center gap-1 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                        <span class="material-symbols-outlined text-[13px]" style="font-variation-settings:'FILL' 1">workspace_premium</span> Premium
                    </span>
                @endif
            </div>
        @endif

        {{-- Title --}}
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 leading-tight mb-3">{{ $listing->title }}</h1>

        {{-- Rating + Location row --}}
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
            @if($listing->area)
                <span class="flex items-center gap-1 text-slate-600">
                    <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                    {{ $listing->area->name }}, Alibaug
                </span>
            @else
                <span class="flex items-center gap-1 text-slate-600">
                    <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                    Alibaug
                </span>
            @endif
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
                        @if($tag->icon)<span class="material-symbols-outlined text-[13px]">{{ $tag->icon }}</span>@endif
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Share row --}}
        <div class="flex items-center gap-2 pt-4 border-t border-slate-100" x-data="{ copied: false }">
            <span class="text-xs text-slate-400 font-medium mr-1">Share:</span>
            <a href="https://wa.me/?text={{ urlencode($listing->title . ' — ' . url()->current()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-[#25D366] bg-[#25D366]/10 hover:bg-[#25D366]/20 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]">chat</span> WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-600 bg-blue-500/10 hover:bg-blue-500/20 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]">share</span> Facebook
            </a>
            <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(()=> copied = false, 2000)"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-full transition-colors">
                <span class="material-symbols-outlined text-[14px]" x-text="copied ? 'check' : 'link'">link</span>
                <span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
            </button>
        </div>
    </div>

    {{-- ── TWO-COLUMN LAYOUT ───────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ━━ MAIN CONTENT ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- About this activity --}}
            @include('listing.partials._about', ['descHeading' => 'About this activity'])

            {{-- Activity Info card --}}
            @if($activityType || $duration || $difficulty || $groupSize)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">info</span>
                        Activity Info
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($activityType)
                            <div class="bg-slate-50 rounded-xl p-4 flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-[22px] mt-0.5 flex-shrink-0">hiking</span>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Activity Type</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ $activityType }}</p>
                                </div>
                            </div>
                        @endif
                        @if($duration)
                            <div class="bg-slate-50 rounded-xl p-4 flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-[22px] mt-0.5 flex-shrink-0">schedule</span>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Duration</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ $duration }}</p>
                                </div>
                            </div>
                        @endif
                        @if($difficulty)
                            <div class="bg-slate-50 rounded-xl p-4 flex items-start gap-3">
                                <span class="material-symbols-outlined text-[22px] mt-0.5 flex-shrink-0 {{ $difficultyClasses['text'] }}">signal_cellular_alt</span>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Difficulty</p>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold border
                                                 {{ $difficultyClasses['bg'] }} {{ $difficultyClasses['text'] }} {{ $difficultyClasses['border'] }}">
                                        {{ ucfirst($difficulty) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        @if($groupSize)
                            <div class="bg-slate-50 rounded-xl p-4 flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-[22px] mt-0.5 flex-shrink-0">group</span>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Group Size</p>
                                    <p class="text-sm font-semibold text-slate-800">{{ $groupSize }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- What's included --}}
            @include('listing.partials._amenities', ['amenitiesHeading' => "What's included"])

            {{-- Map --}}
            @include('listing.partials._map')

            {{-- Reviews --}}
            @include('listing.partials._reviews')

        </div>
        {{-- END MAIN CONTENT --}}

        {{-- ━━ SIDEBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        @include('listing.partials._sidebar', [
            'sidebarCtaLabel'    => 'Book Activity',
            'sidebarShowDates'   => true,
            'sidebarDateLabel'   => 'Activity Date',
            'sidebarDate2Label'  => 'Return Date',
            'sidebarShowGuests'  => true,
            'sidebarGuestsLabel' => 'Group size',
        ])

    </div>
    {{-- END TWO-COLUMN --}}

    {{-- Related listings --}}
    @include('listing.partials._related')

</div>
</div>

{{-- Mobile sticky bar --}}
@include('listing.partials._mobile-bar', [
    'mobileCta'      => 'Book Now',
    'mobileBarLabel' => $duration ? $duration : 'Contact for details',
])

{{-- Listing Card --}}
@php
    $rating      = $listing->getAverageRating();
    $reviewCount = $listing->approved_reviews_count ?? null;
    $catSlug     = $listing->category->slug ?? '';
    $priceLabel  = match($catSlug) {
        'stay'        => '/night',
        'eat'         => ' for 2',
        'events'      => ' onwards',
        'explore'     => '/person',
        default       => '',
    };
@endphp

<div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full relative">

    {{-- Main Clickable Overlay --}}
    <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
       class="absolute inset-0 z-10"
       aria-label="View {{ $listing->title }}"></a>

    {{-- ── IMAGE ──────────────────────────────────────────────────────── --}}
    <div class="relative aspect-[4/3] overflow-hidden flex-shrink-0 bg-slate-100">
        @if($listing->getPrimaryImageUrl())
            <img src="{{ $listing->getPrimaryImageUrl() }}"
                 alt="{{ $listing->title }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                 loading="lazy">
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-slate-50">
                <span class="material-symbols-outlined text-4xl text-slate-300">image</span>
                <span class="text-xs text-slate-400">No photo yet</span>
            </div>
        @endif

        {{-- Gradient overlay for bottom readability --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent pointer-events-none"></div>

        {{-- Top-Left: Feature / Premium badge --}}
        <div class="absolute top-3 left-3 z-20 pointer-events-none flex flex-col gap-1">
            @if($listing->is_featured)
                <span class="inline-flex items-center gap-1 bg-primary text-white px-2.5 py-1 rounded-full text-[10px] font-bold shadow">
                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">star</span> Featured
                </span>
            @elseif($listing->is_premium)
                <span class="inline-flex items-center gap-1 bg-amber-500 text-white px-2.5 py-1 rounded-full text-[10px] font-bold shadow">
                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">workspace_premium</span> Premium
                </span>
            @endif
        </div>

        {{-- Top-Right: Wishlist heart button --}}
        <div class="absolute top-3 right-3 z-20">
            @auth
                @php $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())->where('listing_id', $listing->id)->exists(); @endphp
                <form method="POST" action="{{ route('wishlist.toggle', $listing) }}">
                    @csrf
                    <button type="submit"
                            title="{{ $isWishlisted ? 'Remove from wishlist' : 'Save' }}"
                            class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow hover:bg-white transition-colors">
                        <span class="material-symbols-outlined text-[18px] {{ $isWishlisted ? 'text-red-500' : 'text-slate-400 hover:text-red-400' }} transition-colors"
                              style="{{ $isWishlisted ? 'font-variation-settings:\'FILL\' 1' : '' }}">favorite</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   title="Sign in to save"
                   class="w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow hover:bg-white transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-slate-400 hover:text-red-400 transition-colors">favorite</span>
                </a>
            @endauth
        </div>

        {{-- Bottom-left: Category / Area tag --}}
        <div class="absolute bottom-3 left-3 z-20 pointer-events-none">
            @if($listing->area)
                <span class="inline-flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-[10px] font-medium">
                    <span class="material-symbols-outlined text-[11px]">location_on</span>{{ $listing->area->name }}
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-[10px] font-medium">
                    {{ $listing->category->name }}
                </span>
            @endif
        </div>

        {{-- Bottom-right: Rating pill --}}
        @if($rating > 0)
        <div class="absolute bottom-3 right-3 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-1 bg-white/90 backdrop-blur-sm text-slate-800 px-2.5 py-1 rounded-full text-[11px] font-bold shadow-sm">
                <span class="material-symbols-outlined text-[13px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
                {{ $rating }}
                @if($reviewCount)
                    <span class="text-slate-400 font-normal">({{ $reviewCount }})</span>
                @endif
            </span>
        </div>
        @elseif(!$rating)
        <div class="absolute bottom-3 right-3 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-1 bg-white/90 backdrop-blur-sm text-slate-500 px-2.5 py-1 rounded-full text-[10px] font-medium shadow-sm">
                New
            </span>
        </div>
        @endif
    </div>

    {{-- ── BODY ────────────────────────────────────────────────────────── --}}
    <div class="p-4 flex flex-col flex-grow pointer-events-none">

        {{-- Title --}}
        <h3 class="font-bold text-slate-900 truncate text-[15px] leading-snug mb-1">{{ $listing->title }}</h3>

        {{-- Location --}}
        <p class="text-xs text-slate-500 truncate mb-2.5">
            {{ $listing->area?->name ? $listing->area->name . ', Alibaug' : 'Alibaug' }}
        </p>

        {{-- Attribute pills (beds/guests/cuisine) --}}
        @php $attrs = $listing->attrs ?? []; @endphp
        @if(!empty($attrs))
        <div class="flex flex-wrap gap-1.5 mb-2.5">
            @if(isset($attrs['guests']))
                <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[11px]">
                    <span class="material-symbols-outlined text-[13px]">group</span> {{ $attrs['guests'] }} guests
                </span>
            @endif
            @if(isset($attrs['bedrooms']))
                <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[11px]">
                    <span class="material-symbols-outlined text-[13px]">bed</span> {{ $attrs['bedrooms'] }} bed
                </span>
            @endif
            @if(isset($attrs['bathrooms']))
                <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[11px]">
                    <span class="material-symbols-outlined text-[13px]">shower</span> {{ $attrs['bathrooms'] }} bath
                </span>
            @endif
            @if(isset($attrs['cuisine']) && empty($attrs['bedrooms']))
                <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[11px]">
                    <span class="material-symbols-outlined text-[13px]">restaurant_menu</span> {{ $attrs['cuisine'] }}
                </span>
            @endif
        </div>
        @endif

        {{-- Price --}}
        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
            @if($listing->price)
                <div>
                    <span class="font-bold text-slate-900 text-base">₹{{ number_format($listing->price) }}</span>
                    @if($priceLabel)
                        <span class="text-slate-500 text-xs">{{ $priceLabel }}</span>
                    @endif
                </div>
            @else
                <span class="text-sm text-slate-500 italic">Contact for pricing</span>
            @endif

            <span class="text-[11px] text-primary font-bold flex items-center gap-0.5 group-hover:gap-1.5 transition-all">
                View <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </span>
        </div>
    </div>
</div>

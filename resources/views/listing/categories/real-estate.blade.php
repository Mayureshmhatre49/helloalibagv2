{{--
  Category partial: Real Estate
  Included from listing/show.blade.php
  Variables available: $listing, $avgRating, $reviewCount, $catSlug, $priceLabel,
                       $relatedListings, $recentlyViewed, $dynAttrs
--}}

@php
    $listingType         = $listing->listingAttributes->where('attribute_key', 'listing_type')->first()?->attribute_value;
    $rePropertyType      = $listing->listingAttributes->where('attribute_key', 're_property_type')->first()?->attribute_value;
    $areaSqft            = $listing->listingAttributes->where('attribute_key', 'area_sqft')->first()?->attribute_value;
    $facing              = $listing->listingAttributes->where('attribute_key', 'facing')->first()?->attribute_value;
    $constructionStatus  = $listing->listingAttributes->where('attribute_key', 'construction_status')->first()?->attribute_value;
    $reraNumber          = $listing->listingAttributes->where('attribute_key', 'rera_number')->first()?->attribute_value;

    $listingTypeBadge = match(strtolower($listingType ?? '')) {
        'for sale'   => ['bg' => 'bg-blue-600',   'text' => 'text-white',  'label' => 'For Sale'],
        'for rent'   => ['bg' => 'bg-amber-500',  'text' => 'text-white',  'label' => 'For Rent'],
        'long lease' => ['bg' => 'bg-purple-600', 'text' => 'text-white',  'label' => 'Long Lease'],
        default      => ['bg' => 'bg-slate-700',  'text' => 'text-white',  'label' => $listingType ?? 'Property'],
    };
@endphp

<div class="bg-slate-50 pb-24 lg:pb-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    {{-- ── BREADCRUMB ──────────────────────────────────────────────────────── --}}
    @include('listing.partials._breadcrumb')

    {{-- ── GALLERY ──────────────────────────────────────────────────────────── --}}
    @include('listing.partials._gallery')

    {{-- ── PROMINENT PRICE + TYPE BADGES ──────────────────────────────────── --}}
    <div class="mb-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                @if($listingType)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-bold {{ $listingTypeBadge['bg'] }} {{ $listingTypeBadge['text'] }}">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1">home</span>
                        {{ $listingTypeBadge['label'] }}
                    </span>
                @endif
                @if($constructionStatus)
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-[14px]">construction</span>
                        {{ $constructionStatus }}
                    </span>
                @endif
                @if($rePropertyType)
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-sm font-medium">
                        <span class="material-symbols-outlined text-[14px]">villa</span>
                        {{ $rePropertyType }}
                    </span>
                @endif
            </div>
            @if($listing->price)
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
                        ₹{{ number_format($listing->price) }}
                    </span>
                    @if($priceLabel)
                        <span class="text-slate-500 text-base">{{ $priceLabel }}</span>
                    @endif
                </div>
                @if($areaSqft)
                    <p class="text-sm text-slate-500 mt-1">
                        ₹{{ number_format(round($listing->price / $areaSqft)) }} / sqft &nbsp;·&nbsp; {{ number_format($areaSqft) }} sqft
                    </p>
                @endif
            @else
                <p class="text-2xl font-bold text-slate-700">Price on request</p>
            @endif
        </div>
        @if($avgRating > 0)
            <div class="flex items-center gap-1.5 flex-shrink-0">
                <span class="material-symbols-outlined text-amber-400 text-[22px]" style="font-variation-settings:'FILL' 1">star</span>
                <span class="text-xl font-bold text-slate-800">{{ $avgRating }}</span>
                <span class="text-sm text-slate-400">({{ $reviewCount }})</span>
            </div>
        @endif
    </div>

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

        {{-- Location + views row --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-sm mb-4">
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

            {{-- Property Specifications card --}}
            @if($listingType || $rePropertyType || $areaSqft || $facing || $constructionStatus || $reraNumber)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">home_work</span>
                        Property Specifications
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                        @if($listingType)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-primary text-[20px]">sell</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Listing Type</p>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $listingTypeBadge['bg'] }} {{ $listingTypeBadge['text'] }}">
                                    {{ $listingTypeBadge['label'] }}
                                </span>
                            </div>
                        @endif

                        @if($rePropertyType)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-primary text-[20px]">villa</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Property Type</p>
                                <p class="text-sm font-semibold text-slate-800 leading-snug">{{ $rePropertyType }}</p>
                            </div>
                        @endif

                        @if($areaSqft)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-primary text-[20px]">square_foot</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Area</p>
                                <p class="text-sm font-semibold text-slate-800">{{ number_format($areaSqft) }} <span class="font-normal text-slate-500">sqft</span></p>
                            </div>
                        @endif

                        @if($facing)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-primary text-[20px]">explore</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Facing</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $facing }}</p>
                            </div>
                        @endif

                        @if($constructionStatus)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-primary text-[20px]">construction</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">Construction</p>
                                <p class="text-sm font-semibold text-slate-800">{{ $constructionStatus }}</p>
                            </div>
                        @endif

                        @if($reraNumber)
                            <div class="bg-slate-50 rounded-xl p-4 flex flex-col gap-1.5">
                                <span class="material-symbols-outlined text-green-600 text-[20px]" style="font-variation-settings:'FILL' 1">verified</span>
                                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wide">RERA No.</p>
                                <div class="flex items-center gap-1.5">
                                    <p class="text-sm font-semibold text-slate-800 break-all leading-snug">{{ $reraNumber }}</p>
                                    <span class="inline-flex items-center gap-0.5 bg-green-50 text-green-700 border border-green-200 px-1.5 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0">
                                        <span class="material-symbols-outlined text-[10px]" style="font-variation-settings:'FILL' 1">verified</span>
                                        Verified
                                    </span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- About this property --}}
            @include('listing.partials._about', ['descHeading' => 'About this property'])

            {{-- Property Features --}}
            @include('listing.partials._amenities', ['amenitiesHeading' => 'Property Features'])

            {{-- Map --}}
            @include('listing.partials._map')

            {{-- Reviews --}}
            @include('listing.partials._reviews')

        </div>
        {{-- END MAIN CONTENT --}}

        {{-- ━━ CUSTOM REAL ESTATE SIDEBAR ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
        <aside class="lg:w-[360px] flex-shrink-0" id="inquiry-section">
            <div class="lg:sticky lg:top-24 space-y-4">

                {{-- Price + Schedule a Visit Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6">

                    {{-- Large price display --}}
                    <div class="mb-5 pb-5 border-b border-slate-100">
                        @if($listing->price)
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">₹{{ number_format($listing->price) }}</span>
                                @if($priceLabel)
                                    <span class="text-slate-500 text-sm">{{ $priceLabel }}</span>
                                @endif
                            </div>
                            @if($areaSqft)
                                <p class="text-xs text-slate-400 mb-2">
                                    ≈ ₹{{ number_format(round($listing->price / $areaSqft)) }} / sqft
                                </p>
                            @endif
                        @else
                            <p class="text-xl font-bold text-slate-700 mb-1">Price on request</p>
                        @endif

                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($listingType)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $listingTypeBadge['bg'] }} {{ $listingTypeBadge['text'] }}">
                                    {{ $listingTypeBadge['label'] }}
                                </span>
                            @endif
                            @if($reraNumber)
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-bold">
                                    <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">verified</span>
                                    RERA: {{ $reraNumber }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Contact buttons --}}
                    <div class="space-y-2.5 mb-5">
                        @if($listing->phone)
                            <a href="tel:{{ $listing->phone }}"
                               class="flex items-center justify-center gap-2 w-full bg-primary text-white py-3 rounded-xl font-bold text-sm hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                                <span class="material-symbols-outlined text-[20px]">call</span>
                                Call Now
                            </a>
                        @endif
                        @if($listing->whatsapp)
                            <a href="https://wa.me/91{{ $listing->whatsapp }}?text={{ urlencode('Hi, I\'m interested in ' . $listing->title . ' – ' . url()->current()) }}"
                               target="_blank"
                               class="flex items-center justify-center gap-2 w-full bg-[#25D366] text-white py-3 rounded-xl font-bold text-sm hover:bg-[#1db954] transition-colors shadow-md shadow-green-500/20">
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                                Chat on WhatsApp
                            </a>
                        @endif
                        @if($listing->email && !$listing->phone && !$listing->whatsapp)
                            <a href="mailto:{{ $listing->email }}"
                               class="flex items-center justify-center gap-2 w-full bg-white border-2 border-slate-200 text-slate-700 py-3 rounded-xl font-bold text-sm hover:border-primary hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                                Send Email
                            </a>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="relative flex items-center gap-3 mb-5">
                        <div class="flex-1 h-px bg-slate-100"></div>
                        <span class="text-xs text-slate-400 font-medium whitespace-nowrap">or schedule a visit</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>

                    {{-- Schedule a Visit Form --}}
                    <form method="POST" action="{{ route('listing.inquiry.store', $listing) }}" class="space-y-2.5">
                        @csrf

                        <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" required
                               placeholder="Your name *"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">

                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               placeholder="Phone number *"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1 px-1">Preferred Visit Date</label>
                            <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-slate-50/50">
                        </div>

                        <textarea name="message" rows="3"
                                  placeholder="Any questions or specific requirements?"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none placeholder:text-slate-400 bg-slate-50/50">{{ old('message') }}</textarea>

                        <button type="submit"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl text-sm font-bold transition-colors shadow-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            Schedule a Visit
                        </button>
                    </form>

                    <p class="flex items-start gap-1.5 text-[11px] text-slate-400 mt-3 leading-snug">
                        <span class="material-symbols-outlined text-[13px] mt-0.5 flex-shrink-0">info</span>
                        This schedules a site visit request. The owner will confirm the date and contact you directly.
                    </p>

                    {{-- Trust signals --}}
                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                        <div>
                            <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">verified</span>
                            <p class="text-[10px] text-slate-500 leading-tight">Verified<br>Listing</p>
                        </div>
                        <div>
                            <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">lock</span>
                            <p class="text-[10px] text-slate-500 leading-tight">Secure<br>Inquiry</p>
                        </div>
                        <div>
                            <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">support_agent</span>
                            <p class="text-[10px] text-slate-500 leading-tight">Local<br>Support</p>
                        </div>
                    </div>
                </div>

                {{-- Owner / Agent Card --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Listed by</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ $listing->creator->getAvatarUrl() }}" alt="{{ $listing->creator->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-900 text-sm truncate">{{ $listing->creator->name }}</p>
                            <p class="text-xs text-slate-400">Member since {{ $listing->creator->created_at->format('M Y') }}</p>
                        </div>
                        <span class="material-symbols-outlined text-primary text-[20px]" title="Verified owner"
                              style="font-variation-settings:'FILL' 1">verified</span>
                    </div>
                </div>

            </div>
        </aside>
        {{-- END SIDEBAR --}}

    </div>
    {{-- END TWO-COLUMN --}}

    {{-- Related listings --}}
    @include('listing.partials._related')

</div>
</div>

{{-- Mobile sticky bar — price prominent + Schedule Visit CTA --}}
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-3 z-40 shadow-[0_-4px_24px_rgba(0,0,0,0.08)]">
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            @if($listing->price)
                <div class="flex items-baseline gap-1">
                    <span class="text-lg font-extrabold text-slate-900">₹{{ number_format($listing->price) }}</span>
                    @if($priceLabel)
                        <span class="text-xs text-slate-400">{{ $priceLabel }}</span>
                    @endif
                </div>
                @if($listingType)
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold mt-0.5 {{ $listingTypeBadge['bg'] }} {{ $listingTypeBadge['text'] }}">
                        {{ $listingTypeBadge['label'] }}
                    </span>
                @endif
            @else
                <span class="text-sm font-semibold text-slate-700">Price on request</span>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            @if($listing->phone)
                <a href="tel:{{ $listing->phone }}"
                   class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">call</span>
                </a>
            @endif
            @if($listing->whatsapp)
                <a href="https://wa.me/91{{ $listing->whatsapp }}?text={{ urlencode('Hi, I\'m interested in ' . $listing->title) }}"
                   target="_blank"
                   class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366]/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                </a>
            @endif
            <button onclick="document.getElementById('inquiry-section').scrollIntoView({behavior: 'smooth'})"
                    class="bg-primary hover:bg-primary/90 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm shadow-primary/20 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                Schedule Visit
            </button>
        </div>
    </div>
</div>

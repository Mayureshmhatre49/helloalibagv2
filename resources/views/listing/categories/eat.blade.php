{{--
    Eat category detail template.
    Partial — no @extends. Included from show.blade.php.
    Available: $listing, $avgRating, $reviewCount, $catSlug, $priceLabel,
               $relatedListings, $recentlyViewed, $dynAttrs.
--}}

@php
    $eatAttrs       = $listing->listingAttributes->pluck('attribute_value', 'attribute_key');

    // Quick-glance bar attributes
    $opensAt        = $eatAttrs->get('opens_at', '');
    $closesAt       = $eatAttrs->get('closes_at', '');
    $avgCostForTwo  = $eatAttrs->get('avg_cost_for_two', '');
    $cuisineRaw     = $eatAttrs->get('cuisine', '');
    $foodType       = $eatAttrs->get('food_type', '');

    $cuisineLabels  = ['malvani'=>'Malvani','konkan'=>'Konkan / Coastal','aagri'=>'Aagri','koli'=>'Koli','seafood'=>'Seafood Specialist','fish_thali'=>'Fish Thali House','farm_to_table'=>'Farm-to-Table','fusion'=>'Fusion / Modern','indian'=>'Indian','coastal'=>'Coastal / Konkan','continental'=>'Continental','cafe'=>'Cafe / Bakery','multi_cuisine'=>'Multi-Cuisine','chinese'=>'Chinese','italian'=>'Italian','beach_shack'=>'Beach Shack'];
    $foodTypeLabels = ['veg'=>'Pure Veg','non-veg'=>'Non-Veg','both'=>'Veg & Non-Veg'];
    $cuisine        = $cuisineLabels[$cuisineRaw] ?? ucwords(str_replace('_', ' ', $cuisineRaw));

    // Open/closed badge — compare current time to opens_at / closes_at
    $isOpenNow = null;
    if ($opensAt && $closesAt) {
        try {
            $now   = \Carbon\Carbon::now();
            $open  = \Carbon\Carbon::createFromFormat('H:i', $opensAt)->setDateFrom($now);
            $close = \Carbon\Carbon::createFromFormat('H:i', $closesAt)->setDateFrom($now);
            // Handle overnight businesses (e.g. 22:00–02:00)
            if ($close->lessThan($open)) {
                $isOpenNow = $now->greaterThanOrEqualTo($open) || $now->lessThan($close);
            } else {
                $isOpenNow = $now->between($open, $close);
            }
        } catch (\Exception $e) {
            $isOpenNow = null;
        }
    }

    // Format hours string
    $hoursDisplay = '';
    if ($opensAt && $closesAt) {
        try {
            $hoursDisplay = \Carbon\Carbon::createFromFormat('H:i', $opensAt)->format('g:i A')
                          . ' – '
                          . \Carbon\Carbon::createFromFormat('H:i', $closesAt)->format('g:i A');
        } catch (\Exception $e) {
            $hoursDisplay = $opensAt . ' – ' . $closesAt;
        }
    }

    // Dining Info Panel data
    $specialties    = array_filter(explode(',', $eatAttrs->get('specialty', '')));
    $vibes          = array_filter(explode(',', $eatAttrs->get('dining_vibe', '')));
    $specialtyLabels= ['fish_thali'=>'Fish Thali','a_la_carte'=>'A La Carte','seafood_platter'=>'Seafood Platter','malvani_thali'=>'Malvani Thali','farm_dining'=>'Farm Dining','tasting_menu'=>'Tasting Menu','beach_shack'=>'Beach Shack','khanawal'=>'Khanawal'];
    $vibeLabels     = ['romantic'=>['favorite','Romantic'],'family_casual'=>['family_restroom','Family Casual'],'beachfront'=>['beach_access','Beachfront'],'garden'=>['park','Garden / Outdoor'],'heritage'=>['account_balance','Heritage'],'lively_bar'=>['local_bar','Lively Bar'],'bohemian'=>['palette','Bohemian'],'fine_dining'=>['hotel_class','Fine Dining']];
    $diningOpts     = array_filter(explode(',', $eatAttrs->get('dining_options', '')));
    $diningFacil    = array_filter(explode(',', $eatAttrs->get('dining_facilities', '')));
    $dietaryOpts    = array_filter(explode(',', $eatAttrs->get('dietary_options', '')));
    $paymentMethods = array_filter(explode(',', $eatAttrs->get('payment_methods', '')));
    $closedOn       = array_filter(explode(',', $eatAttrs->get('closed_on', '')));
    $mustTry        = $eatAttrs->get('must_try_dishes', '');
    $resvPolicy     = $eatAttrs->get('reservation_policy', '');
    $zomatoUrl      = $eatAttrs->get('zomato_url', '');
    $swiggyUrl      = $eatAttrs->get('swiggy_url', '');
    $magicpinUrl    = $eatAttrs->get('magicpin_url', '');
    $instaHandle    = $eatAttrs->get('instagram_handle', '');

    // Available On platforms
    $availableOn    = array_filter(explode(',', $eatAttrs->get('available_on', '')));
    $dineoutUrl     = $eatAttrs->get('dineout_url', '');
    $eazyDinerUrl   = $eatAttrs->get('eazydiner_url', '');
    $googleUrl      = $eatAttrs->get('google_url', '');

    $platformDefs = [
        'zomato'    => ['label'=>'Zomato',    'color'=>'#E23744', 'bg'=>'#FEF2F2', 'text'=>'#B91C1C', 'url_key'=>'zomato_url',    'desc'=>'Menu & Reviews'],
        'swiggy'    => ['label'=>'Swiggy',    'color'=>'#FC8019', 'bg'=>'#FFF7ED', 'text'=>'#C2410C', 'url_key'=>'swiggy_url',    'desc'=>'Food Delivery'],
        'magicpin'  => ['label'=>'Magicpin',  'color'=>'#E91E8C', 'bg'=>'#FDF2F8', 'text'=>'#BE185D', 'url_key'=>'magicpin_url',  'desc'=>'Offers & Discovery'],
        'dineout'   => ['label'=>'Dineout',   'color'=>'#C0392B', 'bg'=>'#FFF5F5', 'text'=>'#991B1B', 'url_key'=>'dineout_url',   'desc'=>'Table Reservations'],
        'eazydiner' => ['label'=>'EazyDiner', 'color'=>'#FF5A5F', 'bg'=>'#FFF5F5', 'text'=>'#BE123C', 'url_key'=>'eazydiner_url', 'desc'=>'Reserve & Earn'],
        'google'    => ['label'=>'Google',    'color'=>'#4285F4', 'bg'=>'#EFF6FF', 'text'=>'#1D4ED8', 'url_key'=>'google_url',    'desc'=>'Google Maps'],
    ];

    $urlMap = ['zomato_url'=>$zomatoUrl,'swiggy_url'=>$swiggyUrl,'magicpin_url'=>$magicpinUrl,'dineout_url'=>$dineoutUrl,'eazydiner_url'=>$eazyDinerUrl,'google_url'=>$googleUrl];
    $activePlatforms = array_filter($availableOn, fn($k) => isset($platformDefs[$k]));
    $hasPlatformSection = count($activePlatforms) > 0;

    $hasDiningPanel = count($diningOpts) || count($diningFacil) || count($dietaryOpts)
                   || count($paymentMethods) || count($closedOn) || count($specialties)
                   || count($vibes) || $mustTry || $resvPolicy || $zomatoUrl || $swiggyUrl
                   || $opensAt || $magicpinUrl || $instaHandle || $hasPlatformSection;

    $diningOptLabels = [
        'dine_in'         => ['restaurant',      'Dine In'],
        'takeaway'        => ['takeout_dining',   'Takeaway'],
        'delivery'        => ['delivery_dining',  'Delivery'],
        'outdoor_seating' => ['deck',             'Outdoor Seating'],
        'live_counter'    => ['storefront',       'Live Counter'],
        'private_dining'  => ['meeting_room',     'Private Dining'],
    ];
    $facilityLabels = [
        'pet_friendly'         => ['pets',          'Pet Friendly'],
        'kid_friendly'         => ['child_care',     'Kid Friendly'],
        'wheelchair_accessible'=> ['accessible',     'Wheelchair OK'],
        'wifi_available'       => ['wifi',           'Free WiFi'],
        'live_music'           => ['music_note',     'Live Music'],
        'air_conditioned'      => ['ac_unit',        'Air Conditioned'],
        'alcohol_served'       => ['local_bar',      'Alcohol Served'],
        'rooftop'              => ['roofing',        'Rooftop'],
        'sea_view'             => ['waves',          'Sea View'],
        'garden_seating'       => ['park',           'Garden Seating'],
    ];
    $dietaryLabels = [
        'veg_options'    => ['eco',              'Veg Options'],
        'vegan_options'  => ['spa',              'Vegan'],
        'jain_options'   => ['self_improvement', 'Jain'],
        'halal_options'  => ['verified',         'Halal'],
        'gluten_free'    => ['grain',            'Gluten Free'],
        'nut_free'       => ['no_food',          'Nut Free'],
    ];
    $paymentLabels = [
        'cash'   => ['payments',   'Cash'],
        'card'   => ['credit_card','Card'],
        'upi'    => ['qr_code_2',  'UPI'],
        'online' => ['smartphone', 'Online'],
    ];
    $resvLabels = [
        'required'    => 'Reservation Required',
        'recommended' => 'Recommended',
        'not_needed'  => 'Walk-ins Welcome',
    ];
    $dayLabels = [
        'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed',
        'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun',
    ];

    // Build Google Maps directions URL
    $mapsQuery    = implode(', ', array_filter([$listing->address, $listing->area?->name, 'Alibaug, Maharashtra, India']));
    $mapsUrl      = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($mapsQuery);
    $hasPlatforms = $zomatoUrl || $swiggyUrl || $magicpinUrl;

    // Menu photos
    $menuPhotos   = $listing->images->filter(fn($i) => ($i->image_type ?? 'gallery') === 'menu')->values();

    // Quick action platforms (ordered by priority: delivery first, then reservations)
    $quickPlatformOrder = ['swiggy', 'zomato', 'eazydiner', 'dineout', 'magicpin', 'google'];
    $quickPlatformActions = ['swiggy'=>'Order','zomato'=>'Order','eazydiner'=>'Reserve','dineout'=>'Reserve','magicpin'=>'Offers','google'=>'Maps'];
@endphp

<div class="bg-slate-50 pb-24 lg:pb-12">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    {{-- ── BREADCRUMB ────────────────────────────────────────────────────── --}}
    @include('listing.partials._breadcrumb')

    {{-- ── GALLERY ──────────────────────────────────────────────────────── --}}
    @include('listing.partials._gallery')

    {{-- ── AT A GLANCE INFO BAR ─────────────────────────────────────────── --}}
    @if($hoursDisplay || $avgCostForTwo || $cuisine || $foodType || $isOpenNow !== null)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex flex-wrap items-center gap-4 mb-5">

            {{-- Open/Closed badge --}}
            @if($isOpenNow === true)
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Open Now
                </span>
            @elseif($isOpenNow === false)
                <span class="inline-flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Closed
                </span>
            @endif

            {{-- Hours --}}
            @if($hoursDisplay)
                <div class="flex items-center gap-2 text-sm text-slate-700">
                    <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                    <span class="font-medium">{{ $hoursDisplay }}</span>
                </div>
            @endif

            {{-- Cost for two --}}
            @if($avgCostForTwo)
                <div class="flex items-center gap-2 text-sm text-slate-700">
                    <span class="material-symbols-outlined text-primary text-[18px]">currency_rupee</span>
                    <span class="font-medium">₹{{ $avgCostForTwo }} for two</span>
                </div>
            @endif

            {{-- Cuisine --}}
            @if($cuisine)
                <div class="flex items-center gap-2 text-sm text-slate-700">
                    <span class="material-symbols-outlined text-primary text-[18px]">restaurant</span>
                    <span class="font-medium">{{ $cuisine }}</span>
                </div>
            @endif

            {{-- Food type (veg / non-veg) --}}
            @if($foodType)
                @php
                    $ftIcon = $foodType === 'veg' ? 'eco' : ($foodType === 'non-veg' ? 'set_meal' : 'restaurant_menu');
                    $ftColor = $foodType === 'veg' ? 'text-emerald-600' : ($foodType === 'non-veg' ? 'text-red-500' : 'text-primary');
                @endphp
                <div class="flex items-center gap-2 text-sm text-slate-700">
                    <span class="material-symbols-outlined {{ $ftColor }} text-[18px]">{{ $ftIcon }}</span>
                    <span class="font-medium">{{ $foodTypeLabels[$foodType] ?? ucwords(str_replace('-', ' ', $foodType)) }}</span>
                </div>
            @endif

        </div>
    @endif

    {{-- ── TITLE + META ─────────────────────────────────────────────────── --}}
    <div class="mb-5">

        {{-- Title --}}
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 leading-tight mb-3">
            {{ $listing->title }}
        </h1>

        {{-- Rating + Location + Views + Featured/Premium badges --}}
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

            @if($listing->is_featured)
                <span class="inline-flex items-center gap-1 bg-primary text-white px-2.5 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">star</span>
                    Featured
                </span>
            @endif
            @if($listing->is_premium)
                <span class="inline-flex items-center gap-1 bg-amber-500 text-white px-2.5 py-1 rounded-full text-xs font-bold">
                    <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">workspace_premium</span>
                    Premium
                </span>
            @endif
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

    {{-- ── QUICK ACTION BAR ─────────────────────────────────────────────── --}}
    @php
        $hasAnyPlatformUrl = false;
        foreach ($quickPlatformOrder as $pk) {
            if (in_array($pk, $activePlatforms) && ($urlMap[$platformDefs[$pk]['url_key'] ?? ''] ?? '')) {
                $hasAnyPlatformUrl = true; break;
            }
        }
    @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="flex flex-wrap gap-2.5">
            {{-- Primary: Directions --}}
            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary/90 transition-colors shadow-sm shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">directions</span>
                Get Directions
            </a>

            {{-- Call --}}
            @if($listing->phone)
                <a href="tel:{{ $listing->phone }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-[18px] text-primary">call</span>
                    Call Now
                </a>
            @endif

            {{-- WhatsApp --}}
            @if($listing->whatsapp)
                <a href="https://wa.me/91{{ $listing->whatsapp }}?text={{ urlencode('Hi, I\'d like to book a table at ' . $listing->title) }}"
                   target="_blank"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#25D366]/10 text-[#25D366] font-bold text-sm hover:bg-[#25D366]/20 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    WhatsApp
                </a>
            @endif

            {{-- Platform order links --}}
            @foreach($quickPlatformOrder as $pk)
                @if(in_array($pk, $activePlatforms) && isset($platformDefs[$pk]))
                    @php
                        $qpd = $platformDefs[$pk];
                        $qurl = $urlMap[$qpd['url_key']] ?? '';
                        $qact = $quickPlatformActions[$pk] ?? 'View';
                    @endphp
                    @if($qurl)
                        <a href="{{ $qurl }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-colors border"
                           style="background-color: {{ $qpd['bg'] }}; color: {{ $qpd['text'] }}; border-color: {{ $qpd['color'] }}30;">
                            <span class="w-4 h-4 rounded flex items-center justify-center text-white text-[9px] font-black flex-shrink-0"
                                  style="background-color: {{ $qpd['color'] }}">{{ strtoupper(substr($qpd['label'], 0, 1)) }}</span>
                            {{ $qact }} on {{ $qpd['label'] }}
                        </a>
                    @endif
                @endif
            @endforeach
        </div>
    </div>

    {{-- ── TWO-COLUMN LAYOUT ────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ── MAIN CONTENT ─────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- 1. Platform Strip (Find Us On) — moved above About --}}
            @if($hasPlatformSection || $instaHandle)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Find Us On</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($activePlatforms as $platformKey)
                            @php
                                $pd  = $platformDefs[$platformKey];
                                $url = $urlMap[$pd['url_key']] ?? '';
                            @endphp
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"
                                   class="group flex items-center gap-3 p-3 rounded-xl border-2 transition-all hover:shadow-md"
                                   style="border-color: {{ $pd['color'] }}30; background-color: {{ $pd['bg'] }};">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm flex-shrink-0 shadow-sm"
                                         style="background-color: {{ $pd['color'] }};">
                                        {{ strtoupper(substr($pd['label'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm leading-none mb-0.5" style="color: {{ $pd['text'] }}">{{ $pd['label'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $pd['desc'] }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-[16px] text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0">open_in_new</span>
                                </a>
                            @else
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2"
                                     style="border-color: {{ $pd['color'] }}30; background-color: {{ $pd['bg'] }};">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm flex-shrink-0"
                                         style="background-color: {{ $pd['color'] }};">
                                        {{ strtoupper(substr($pd['label'], 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm leading-none mb-0.5" style="color: {{ $pd['text'] }}">{{ $pd['label'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $pd['desc'] }}</p>
                                    </div>
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full" style="background-color: {{ $pd['color'] }}20; color: {{ $pd['text'] }}">Listed</span>
                                </div>
                            @endif
                        @endforeach

                        @if($instaHandle)
                            <a href="https://instagram.com/{{ ltrim($instaHandle, '@') }}" target="_blank" rel="noopener"
                               class="group flex items-center gap-3 p-3 rounded-xl border-2 transition-all hover:shadow-md"
                               style="border-color: #E1306C30; background-color: #FFF0F5;">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                     style="background: linear-gradient(135deg, #F58529 0%, #DD2A7B 50%, #8134AF 100%);">
                                    <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm leading-none mb-0.5 text-pink-700">Instagram</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $instaHandle }}</p>
                                </div>
                                <span class="material-symbols-outlined text-[16px] text-slate-300 group-hover:text-pink-400 transition-colors flex-shrink-0">open_in_new</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 2. Menu Photos --}}
            @if($menuPhotos->count() > 0)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-amber-500">menu_book</span>
                        Menu &amp; Food
                    </h2>
                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none snap-x snap-mandatory">
                        @foreach($menuPhotos as $photo)
                            <div class="flex-shrink-0 w-48 snap-start rounded-xl overflow-hidden border border-slate-100 aspect-[3/4] bg-slate-100 cursor-zoom-in"
                                 x-data="{ open: false }" @click="open = true">
                                <img src="{{ $photo->url }}" alt="{{ $listing->title }} menu"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                {{-- Fullscreen lightbox --}}
                                <div x-show="open" x-cloak @click.stop="open = false"
                                     class="fixed inset-0 z-[70] bg-black/85 flex items-center justify-center p-4"
                                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <img src="{{ $photo->url }}" alt="{{ $listing->title }} menu"
                                         class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
                                    <button @click.stop="open = false" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center hover:bg-white/30 transition-colors">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Scroll to see all · tap to zoom</p>
                </div>
            @endif

            {{-- 3. About --}}
            @include('listing.partials._about', ['descHeading' => 'About the restaurant'])

            {{-- 2. Dining Info Panel --}}
            @if($hasDiningPanel)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">restaurant</span>
                        Dining Info
                    </h2>

                    {{-- Hours, reservation policy, closed days --}}
                    @if($opensAt || $closesAt || $resvPolicy || count($closedOn))
                        <div class="flex flex-wrap gap-3 text-sm">
                            @if($hoursDisplay)
                                <span class="inline-flex items-center gap-1.5 bg-slate-100 rounded-full px-3 py-1.5 font-medium text-slate-700">
                                    <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
                                    {{ $hoursDisplay }}
                                </span>
                            @endif
                            @if($resvPolicy)
                                <span class="inline-flex items-center gap-1.5 bg-slate-100 rounded-full px-3 py-1.5 font-medium text-slate-700">
                                    <span class="material-symbols-outlined text-[16px] text-primary">event_available</span>
                                    {{ $resvLabels[$resvPolicy] ?? $resvPolicy }}
                                </span>
                            @endif
                            @if(count($closedOn))
                                <span class="inline-flex items-center gap-1.5 bg-red-50 border border-red-100 rounded-full px-3 py-1.5 font-medium text-red-700">
                                    <span class="material-symbols-outlined text-[16px]">event_busy</span>
                                    Closed: {{ implode(', ', array_map(fn($d) => $dayLabels[$d] ?? $d, $closedOn)) }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Dining Options --}}
                    @if(count($diningOpts))
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Order / Dine Options</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($diningOpts as $opt)
                                    @if(isset($diningOptLabels[$opt]))
                                        <span class="inline-flex items-center gap-1.5 bg-primary/10 text-primary rounded-xl px-3 py-1.5 text-sm font-semibold">
                                            <span class="material-symbols-outlined text-[16px]">{{ $diningOptLabels[$opt][0] }}</span>
                                            {{ $diningOptLabels[$opt][1] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Specialty & Vibe --}}
                    @if(count($specialties) || count($vibes))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @if(count($specialties))
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Specialty</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($specialties as $sp)
                                            @if(isset($specialtyLabels[$sp]))
                                                <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 text-amber-800 rounded-full px-3 py-1 text-xs font-bold">
                                                    <span class="material-symbols-outlined text-[13px]">restaurant_menu</span>
                                                    {{ $specialtyLabels[$sp] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(count($vibes))
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Vibe</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($vibes as $vb)
                                            @if(isset($vibeLabels[$vb]))
                                                <span class="inline-flex items-center gap-1.5 bg-violet-50 border border-violet-100 text-violet-800 rounded-full px-3 py-1 text-xs font-bold">
                                                    <span class="material-symbols-outlined text-[13px]">{{ $vibeLabels[$vb][0] }}</span>
                                                    {{ $vibeLabels[$vb][1] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Facilities & Atmosphere --}}
                    @if(count($diningFacil))
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Facilities &amp; Atmosphere</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($diningFacil as $fac)
                                    @if(isset($facilityLabels[$fac]))
                                        <div class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-100 bg-slate-50/60">
                                            <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0">{{ $facilityLabels[$fac][0] }}</span>
                                            <span class="text-sm text-slate-700 font-medium leading-tight">{{ $facilityLabels[$fac][1] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Must-Try Dishes --}}
                    @if($mustTry)
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Must-Try Dishes</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(array_filter(array_map('trim', explode(',', $mustTry))) as $dish)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-200 text-amber-800 rounded-full px-3 py-1 text-sm font-semibold">
                                        <span class="material-symbols-outlined text-[14px]">local_dining</span>
                                        {{ $dish }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Dietary Options + Payment Methods --}}
                    @if(count($dietaryOpts) || count($paymentMethods))
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @if(count($dietaryOpts))
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Dietary Options</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($dietaryOpts as $d)
                                            @if(isset($dietaryLabels[$d]))
                                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-full px-3 py-1 text-xs font-bold">
                                                    <span class="material-symbols-outlined text-[13px]">{{ $dietaryLabels[$d][0] }}</span>
                                                    {{ $dietaryLabels[$d][1] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(count($paymentMethods))
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2.5">Payment Accepted</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($paymentMethods as $pm)
                                            @if(isset($paymentLabels[$pm]))
                                                <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-bold">
                                                    <span class="material-symbols-outlined text-[13px]">{{ $paymentLabels[$pm][0] }}</span>
                                                    {{ $paymentLabels[$pm][1] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            @endif
            {{-- /Dining Info Panel --}}

            {{-- 3. Amenities --}}
            @include('listing.partials._amenities', ['amenitiesHeading' => 'Facilities'])

            {{-- 4. Map --}}
            @include('listing.partials._map')

            {{-- 5. Reviews --}}
            @include('listing.partials._reviews')

        </div>
        {{-- /main content --}}

        {{-- ── SIDEBAR (custom — eat version) ──────────────────────────── --}}
        <aside class="lg:w-[360px] flex-shrink-0" id="inquiry-section">
            <div class="lg:sticky lg:top-24 space-y-4">

                {{-- Price + Inquiry card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6">

                    {{-- Price or contact for pricing --}}
                    <div class="mb-4 pb-4 border-b border-slate-100">
                        @if($listing->price)
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-bold text-slate-900">₹{{ number_format($listing->price) }}</span>
                                @if($priceLabel)
                                    <span class="text-slate-500 text-sm">{{ $priceLabel }}</span>
                                @endif
                            </div>
                        @else
                            <p class="text-lg font-semibold text-slate-700">Contact for pricing</p>
                        @endif
                        @if($avgRating > 0)
                            <div class="flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-amber-400 text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                                <span class="text-sm font-bold text-slate-800">{{ $avgRating }}</span>
                                <span class="text-sm text-slate-400">· {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="relative flex items-center gap-3 mb-5">
                        <div class="flex-1 h-px bg-slate-100"></div>
                        <span class="text-xs text-slate-400 font-medium">Make a reservation</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>

                    {{-- Inquiry form --}}
                    <form method="POST" action="{{ route('listing.inquiry.store', $listing) }}" class="space-y-2.5">
                        @csrf
                        <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" required
                               placeholder="Your name *"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                        <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}" required
                               placeholder="Email address *"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               placeholder="Phone number"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                        <textarea name="message" rows="3" required
                                  placeholder="Tell us your preferred date, time and party size... *"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none placeholder:text-slate-400 bg-slate-50/50">{{ old('message') }}</textarea>
                        <button type="submit"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl text-sm font-bold transition-colors shadow-md">
                            Make a Reservation
                        </button>
                    </form>

                    <p class="flex items-start gap-1.5 text-[11px] text-slate-400 mt-3 leading-snug">
                        <span class="material-symbols-outlined text-[13px] mt-0.5 flex-shrink-0">info</span>
                        This sends a reservation request directly to the restaurant. They will confirm availability and contact you.
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

                {{-- Owner Card --}}
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
        {{-- /sidebar --}}

    </div>
    {{-- /two-column layout --}}

    {{-- ── RELATED + RECENTLY VIEWED ───────────────────────────────────── --}}
    @include('listing.partials._related')

</div>
</div>

{{-- ── MOBILE STICKY BAR ────────────────────────────────────────────── --}}
@include('listing.partials._mobile-bar', [
    'mobileCta'      => 'Reserve Table',
    'mobileBarLabel' => 'Contact for pricing',
])

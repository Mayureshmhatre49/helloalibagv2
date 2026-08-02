@extends('layouts.app')

@section('title', 'How to Reach Alibaug from Mumbai — Ferry, Road, Train Guide 2026')
@section('meta_description', 'Complete 2026 guide on how to reach Alibaug from Mumbai, Pune, Thane, and Navi Mumbai. Ferry timings, ticket prices, road distance, RoRo car ferry, train route, and travel tips.')

@push('styles')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TravelAction",
    "name": "Travel from Mumbai to Alibaug",
    "fromLocation": { "@type": "Place", "name": "Mumbai", "address": { "@type": "PostalAddress", "addressRegion": "Maharashtra", "addressCountry": "IN" } },
    "toLocation": { "@type": "Place", "name": "Alibaug", "address": { "@type": "PostalAddress", "addressRegion": "Maharashtra", "addressCountry": "IN" } },
    "distance": "95 km by road, 19 nautical miles by ferry"
}
</script>
@endpush

@section('content')
{{-- ── HERO ────────────────────────────────────────────────────────────── --}}
<section class="relative bg-gradient-to-br from-[#0b3d91] via-[#1f5fc7] to-[#4a8de0] overflow-hidden">
    <div class="absolute inset-x-0 bottom-0 h-32 opacity-20" style="background: radial-gradient(ellipse at bottom, rgba(255,255,255,0.4), transparent 70%);"></div>
    <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <nav class="flex items-center gap-2 text-white/70 text-sm font-medium mb-8" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-white">How to Reach</span>
        </nav>

        <div class="grid lg:grid-cols-[1fr_auto] gap-10 items-end">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 text-white px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                    <span class="material-symbols-outlined text-[16px]">route</span> Travel Guide
                </div>
                <h1 class="text-white font-serif font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight mb-4">
                    How to Reach <span class="text-[#f5c842]">Alibaug</span>
                </h1>
                <p class="text-white/85 text-lg md:text-xl font-medium max-w-2xl leading-relaxed">
                    Four ways from Mumbai, three from Pune. Compare ferry vs road, find timings, and choose the fastest route — updated for 2026.
                </p>
            </div>

            {{-- Quick stats card --}}
            <div class="bg-white/12 backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-2xl w-full lg:min-w-[300px]">
                <p class="text-white/70 text-[11px] uppercase tracking-[0.18em] font-bold mb-4">From Mumbai</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">directions_boat</span>
                            <span class="text-white text-sm font-medium">Ferry</span>
                        </div>
                        <span class="text-white font-bold tabular-nums">~1h 30m</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">directions_car</span>
                            <span class="text-white text-sm font-medium">Road</span>
                        </div>
                        <span class="text-white font-bold tabular-nums">2.5–4h</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">train</span>
                            <span class="text-white text-sm font-medium">Train + Cab</span>
                        </div>
                        <span class="text-white font-bold tabular-nums">~3h</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">directions_bus</span>
                            <span class="text-white text-sm font-medium">Bus</span>
                        </div>
                        <span class="text-white font-bold tabular-nums">3–4h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── ROUTE COMPARISON ────────────────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
    <div class="text-center max-w-3xl mx-auto mb-10">
        <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3">Four Routes Compared</p>
        <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-3">Which route is right for you?</h2>
        <p class="text-slate-600 text-base sm:text-lg">Each option has its sweet spot — pick based on your group size, luggage, and how flexible your timing is.</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Ferry --}}
        <article class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
            <div class="bg-gradient-to-br from-primary to-primary-dark text-white p-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings:'FILL' 1">directions_boat</span>
                    <div>
                        <p class="text-white/80 text-[11px] uppercase tracking-wider font-bold">Recommended</p>
                        <h3 class="text-2xl font-bold leading-tight">Ferry from Gateway of India</h3>
                    </div>
                </div>
                <p class="text-white/85 text-sm font-medium">Gateway of India → Mandwa Jetty → Alibaug (cab/auto)</p>
            </div>
            <div class="p-6">
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Total time</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">~1h 30m (ferry 60m + cab 30m)</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Foot passenger</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹150–₹280</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">With car (M2M/RoRo)</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹1,200–₹2,500</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Frequency</dt>
                        <dd class="text-slate-900 font-bold">Every 30–60 min</dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-text-secondary font-medium">Best for</dt>
                        <dd class="text-emerald-600 font-bold">Couples, small groups, scenic ride</dd>
                    </div>
                </dl>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-900 mt-4 leading-relaxed">
                    <strong class="font-bold">Pro tip:</strong> Book <a href="https://www.m2mferries.com/schedule" target="_blank" rel="noopener" class="underline hover:text-blue-700">M2M/RoRo</a> tickets online during weekends — last ferry from Mandwa departs around 6:00 PM. Monsoon (June–Sep) ferries are often suspended.
                </div>
            </div>
        </article>

        {{-- Road --}}
        <article class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 text-white p-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings:'FILL' 1">directions_car</span>
                    <div>
                        <p class="text-white/70 text-[11px] uppercase tracking-wider font-bold">Most Flexible</p>
                        <h3 class="text-2xl font-bold leading-tight">By Road via NH66</h3>
                    </div>
                </div>
                <p class="text-white/80 text-sm font-medium">Mumbai → Vashi → Panvel → Pen → Alibaug</p>
            </div>
            <div class="p-6">
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Distance</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">~95 km</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Travel time</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">2.5–4h (traffic dependent)</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Tolls</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹150–₹250</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-border-light">
                        <dt class="text-text-secondary font-medium">Fuel (one way)</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹600–₹900</dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-text-secondary font-medium">Best for</dt>
                        <dd class="text-amber-600 font-bold">Families, luggage, flexible timing</dd>
                    </div>
                </dl>
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-900 mt-4 leading-relaxed">
                    <strong class="font-bold">Avoid:</strong> Friday 5–9 PM (out) and Sunday 5–10 PM (back) — Mumbai weekend traffic adds 1–2 hours. Leave before 2 PM or after 10 PM for smooth driving.
                </div>
            </div>
        </article>
    </div>
</section>

{{-- ── FERRY SCHEDULE (summary — full timetable lives at /ferry-schedule) ── --}}
<section class="bg-white border-y border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <div class="max-w-3xl">
            <p class="text-slate-600 text-xs uppercase tracking-[0.18em] font-bold mb-3">Ferry Schedule</p>
            <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-4">Mumbai to Mandwa ferry timings</h2>
            <p class="text-slate-600 text-base leading-relaxed mb-6">
                Six operators across three ferry classes run scheduled crossings between Gateway of India and Mandwa Jetty year-round, weather permitting. Mandwa is a 20-minute drive from Alibaug town. We keep the full timetable — every sailing for M2M/RoRo, PNP, Ajanta, Maldar and Apollo, in both directions — on its own page.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('page.ferry-schedule') }}"
                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-6 py-3.5 rounded-xl font-bold text-sm transition-colors shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">directions_boat</span>
                    See full ferry timetable
                </a>
                <a href="https://www.m2mferries.com/schedule" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-700 px-6 py-3.5 rounded-xl font-bold text-sm transition-colors">
                    Book M2M / RoRo
                </a>
            </div>
            <p class="text-slate-600 text-xs mt-5 leading-relaxed">
                Ferries are frequently cancelled June through September due to rough seas — always confirm before heading to Gateway of India.
            </p>
        </div>
    </div>
</section>

{{-- ── FROM OTHER CITIES ───────────────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
    <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3 text-center">From Other Cities</p>
    <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-10 text-center">Reaching Alibaug from beyond Mumbai</h2>

    <div class="grid md:grid-cols-3 gap-5">
        @php
            $cities = [
                ['name' => 'Pune', 'distance' => '~145 km', 'time' => '3–4 hours', 'route' => 'Expressway → Khopoli → Pen → Alibaug', 'icon' => 'directions_car', 'tip' => 'Mumbai-Pune Expressway adds tolls but saves 30+ minutes vs old highway.'],
                ['name' => 'Thane', 'distance' => '~85 km', 'time' => '2–3 hours', 'route' => 'Mulund → Panvel → NH66 → Pen → Alibaug', 'icon' => 'directions_car', 'tip' => 'Avoid Thane-Belapur road during morning peak (8–10 AM weekdays).'],
                ['name' => 'Navi Mumbai', 'distance' => '~70 km', 'time' => '1.5–2.5 hours', 'route' => 'Belapur → Panvel → NH66 → Pen → Alibaug', 'icon' => 'directions_car', 'tip' => 'Fastest land route to Alibaug. Watch for narrow stretches past Pen.'],
            ];
        @endphp

        @foreach ($cities as $city)
            <article class="bg-white border border-border-light rounded-2xl p-6 hover:border-primary/30 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary text-[24px]">{{ $city['icon'] }}</span>
                    </div>
                    <h3 class="text-slate-900 font-bold text-xl">From {{ $city['name'] }}</h3>
                </div>
                <dl class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between items-center">
                        <dt class="text-text-secondary">Distance</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">{{ $city['distance'] }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-text-secondary">Travel time</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">{{ $city['time'] }}</dd>
                    </div>
                </dl>
                <p class="text-text-secondary text-xs mb-3 leading-relaxed"><strong class="text-slate-700">Route:</strong> {{ $city['route'] }}</p>
                <div class="bg-slate-50 rounded-lg p-3 text-xs text-slate-700 leading-relaxed border border-slate-100">
                    <strong class="text-slate-900">Tip:</strong> {{ $city['tip'] }}
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- ── TRAIN + BUS ─────────────────────────────────────────────────────── --}}
<section class="bg-white border-y border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <div class="grid md:grid-cols-2 gap-6">
            <article class="bg-slate-50 border border-border-light rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-violet-600 text-[24px]" style="font-variation-settings:'FILL' 1">train</span>
                    </div>
                    <h3 class="text-slate-900 font-bold text-xl">By Train</h3>
                </div>
                <p class="text-slate-700 text-sm leading-relaxed mb-4">
                    The nearest railway station is <strong>Pen</strong> (~30 km from Alibaug). Trains from Mumbai (CST, LTT) and Pune stop at Pen as part of the Konkan and Central Railway lines.
                </p>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Mumbai CST → Pen</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">~2 hours</dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Pen → Alibaug (cab)</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">~45 min, ₹400–₹600</dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Total cost</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹150–₹700</dd>
                    </div>
                </dl>
                <p class="text-text-secondary text-xs mt-4 leading-relaxed">Book at <a href="https://www.irctc.co.in" target="_blank" rel="noopener" class="text-primary font-semibold underline hover:text-primary-dark">irctc.co.in</a>. Limited direct trains — check schedules a day in advance.</p>
            </article>

            <article class="bg-slate-50 border border-border-light rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-600 text-[24px]" style="font-variation-settings:'FILL' 1">directions_bus</span>
                    </div>
                    <h3 class="text-slate-900 font-bold text-xl">By Bus (MSRTC)</h3>
                </div>
                <p class="text-slate-700 text-sm leading-relaxed mb-4">
                    Maharashtra State Road Transport (MSRTC) operates regular non-AC and AC buses from <strong>Mumbai Central</strong> and <strong>Panvel</strong> to Alibaug bus stand.
                </p>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Travel time</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">3–4 hours</dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Cost</dt>
                        <dd class="text-slate-900 font-bold tabular-nums">₹150–₹300</dd>
                    </div>
                    <div class="flex justify-between items-center py-1.5">
                        <dt class="text-text-secondary">Frequency</dt>
                        <dd class="text-slate-900 font-bold">Hourly (peak)</dd>
                    </div>
                </dl>
                <p class="text-text-secondary text-xs mt-4 leading-relaxed">Book at <a href="https://msrtc.maharashtra.gov.in" target="_blank" rel="noopener" class="text-primary font-semibold underline hover:text-primary-dark">msrtc.maharashtra.gov.in</a>. Cheapest option but slowest.</p>
            </article>
        </div>
    </div>
</section>

{{-- ── MAP ─────────────────────────────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
    <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3 text-center">Route Map</p>
    <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-8 text-center">Mumbai to Alibaug</h2>
    <div class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-sm">
        <div class="aspect-[16/9]">
            <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d241317.8291!2d72.7!3d18.9!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai!3m2!1d19.0760!2d72.8777!4m5!1s0x3be873f4e3cfd80d%3A0x1cd7a2529d205e0!2sAlibaug!3m2!1d18.6414!2d72.8722!5e0!3m2!1sen!2sin!4v1709900000000!5m2!1sen!2sin"
                    title="Mumbai to Alibaug route map"
                    class="w-full h-full border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- ── FAQ ─────────────────────────────────────────────────────────────── --}}
<section class="bg-white border-t border-border-light">
    <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3 text-center">FAQs</p>
        <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-10 text-center">Common questions about reaching Alibaug</h2>

        <div class="space-y-3" x-data="{ open: 0 }">
            @php
                $transitFaqs = [
                    ['q' => 'What is the fastest way to reach Alibaug from Mumbai?', 'a' => 'The standard passenger ferries (PNP, Ajanta, Maldar, Apollo) from Gateway of India are the fastest, taking around 60 minutes by sea plus a 20–30 minute cab/auto to Alibaug town. Total door-to-door is roughly 1 hour 30 minutes.'],
                    ['q' => 'Are ferries running during the monsoon?', 'a' => 'Most ferries suspend operations from mid-June to mid-September due to rough sea conditions. M2M/RoRo typically does not operate during monsoon. PNP, Ajanta, Maldar, and Apollo may run intermittently on calm days. Always check operator websites or call before heading to the jetty.'],
                    ['q' => 'Can I take my car to Alibaug?', 'a' => 'Yes, via two routes — drive the full 95 km via NH66 (Panvel-Pen-Alibaug), or take the M2M/RoRo car ferry from Bhaucha Dhakka (Mazgaon) to Mandwa for ₹1,200–₹2,500. M2M/RoRo saves time and traffic stress but books up fast on weekends.'],
                    ['q' => 'What time does the last ferry leave Mandwa for Mumbai?', 'a' => 'Last ferries from Mandwa typically depart around 6:00 PM–8:00 PM depending on operator. Times shift seasonally — confirm with M2M/RoRo or the standard ferry operators (PNP, Ajanta, Maldar, Apollo) on the day of travel. Missing the last ferry means a 3+ hour road journey back.'],
                    ['q' => 'Is there a direct train to Alibaug?', 'a' => 'No. The nearest railway station is Pen (~30 km from Alibaug). From Pen, take a pre-paid taxi or auto (₹400–₹600, 45 min) to reach Alibaug town.'],
                    ['q' => 'How much does a typical Mumbai-Alibaug trip cost?', 'a' => 'Cheapest: bus (₹150–₹300 per person). Ferry + cab: ₹350–₹500 per person. Road by car (4 people): ₹250–₹400 per person including fuel and tolls. Train + cab: ₹150–₹700 per person.'],
                    ['q' => 'Can I reach Alibaug by air?', 'a' => 'There is no commercial airport in Alibaug. The nearest airport is Mumbai (CSMIA), about 110 km away (2.5–4 hours by road). A small helipad exists for chartered helicopter services from Mumbai for premium travelers.'],
                    ['q' => 'Is it better to drive or take the ferry?', 'a' => 'Ferry is better for couples or small groups without much luggage — faster, scenic, and traffic-free. Road is better for families with children, heavy luggage, or if you want to stop en route (Karjat, Pen markets). Avoid Friday evenings and Sunday nights for road travel.'],
                ];
            @endphp

            @foreach ($transitFaqs as $i => $faq)
                <div class="bg-white border border-border-light rounded-xl overflow-hidden hover:border-primary/30 transition-colors">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}" type="button"
                            class="w-full text-left px-5 py-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors"
                            :aria-expanded="open === {{ $i }} ? 'true' : 'false'">
                        <span class="text-slate-900 font-semibold text-base pr-4">{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 flex-shrink-0"
                              :class="open === {{ $i }} ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="open === {{ $i }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="px-5 pb-4 text-slate-600 text-sm leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ─────────────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-primary to-primary-dark text-white">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <h2 class="font-serif font-bold text-3xl md:text-4xl mb-3 tracking-tight">Ready to plan your stay?</h2>
        <p class="text-white/85 text-base sm:text-lg max-w-2xl mx-auto mb-8 leading-relaxed">Browse villas, dining, and experiences across Alibaug — handpicked, verified, and ready to book.</p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('search') }}" class="bg-white text-primary font-bold text-sm px-6 py-3 rounded-xl hover:bg-white/95 transition-colors shadow-lg inline-flex items-center gap-2">
                Browse Listings <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
            <a href="{{ route('weather.index') }}" class="bg-white/15 backdrop-blur border border-white/25 text-white font-bold text-sm px-6 py-3 rounded-xl hover:bg-white/20 transition-colors inline-flex items-center gap-2">
                Check Weather <span class="material-symbols-outlined text-[18px]">cloud</span>
            </a>
        </div>
    </div>
</section>

{{-- FAQ Schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type":"Question","name":"What is the fastest way to reach Alibaug from Mumbai?","acceptedAnswer":{"@type":"Answer","text":"The standard passenger ferries (PNP, Ajanta, Maldar, Apollo) from Gateway of India are the fastest, taking around 60 minutes by sea plus a 20–30 minute cab/auto to Alibaug town."}},
        {"@type":"Question","name":"Are ferries running during the monsoon?","acceptedAnswer":{"@type":"Answer","text":"Most ferries suspend operations from mid-June to mid-September due to rough sea conditions. Always check operator websites before heading to the jetty."}},
        {"@type":"Question","name":"Can I take my car to Alibaug?","acceptedAnswer":{"@type":"Answer","text":"Yes — drive the full 95 km via NH66, or take the M2M/RoRo car ferry from Bhaucha Dhakka to Mandwa for ₹1,200–₹2,500."}},
        {"@type":"Question","name":"What time does the last ferry leave Mandwa for Mumbai?","acceptedAnswer":{"@type":"Answer","text":"Last ferries from Mandwa typically depart around 6:00 PM. Times shift seasonally — confirm with the operator on the day of travel."}},
        {"@type":"Question","name":"Is there a direct train to Alibaug?","acceptedAnswer":{"@type":"Answer","text":"No. The nearest railway station is Pen (~30 km from Alibaug). From Pen, take a pre-paid taxi or auto for the remaining journey."}},
        {"@type":"Question","name":"How much does a typical Mumbai-Alibaug trip cost?","acceptedAnswer":{"@type":"Answer","text":"Cheapest is bus at ₹150–₹300. Ferry plus cab is ₹350–₹500 per person. Road by car splits to roughly ₹250–₹400 per person for a group of 4."}},
        {"@type":"Question","name":"Can I reach Alibaug by air?","acceptedAnswer":{"@type":"Answer","text":"There is no commercial airport in Alibaug. The nearest airport is Mumbai (CSMIA), about 110 km away."}},
        {"@type":"Question","name":"Is it better to drive or take the ferry?","acceptedAnswer":{"@type":"Answer","text":"Ferry is better for couples or small groups — faster, scenic, traffic-free. Road is better for families with luggage or stops en route."}}
    ]
}
</script>
@endsection

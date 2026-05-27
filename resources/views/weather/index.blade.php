@extends('layouts.app')

@section('title', 'Alibaug Weather Forecast — 7-Day Forecast & Best Time to Visit')
@section('meta_description', 'Live Alibaug weather and 7-day forecast. Current temperature, rain probability, sunrise/sunset, UV index, plus a month-by-month guide on the best time to visit Alibaug.')

@push('styles')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Alibaug Weather Forecast",
    "description": "Live weather, 7-day forecast, and seasonal travel guide for Alibaug, Maharashtra.",
    "url": "{{ route('weather.index') }}",
    "about": {
        "@type": "Place",
        "name": "Alibaug",
        "geo": { "@type": "GeoCoordinates", "latitude": 18.6414, "longitude": 72.8722 }
    }
}
</script>
@endpush

@section('content')
@php
    $current = $forecast['current'];
    $isDay = $current['is_day'];
    $heroGradient = $isDay
        ? 'linear-gradient(135deg, #0b3d91 0%, #1f5fc7 55%, #4a8de0 100%)'
        : 'linear-gradient(135deg, #0a1f4d 0%, #1a2f5e 55%, #2d4373 100%)';

    // Useful aggregates for the SEO sidebar
    $weekHigh = !empty($forecast['days']) ? max(array_column($forecast['days'], 'temp_max')) : null;
    $weekLow = !empty($forecast['days']) ? min(array_column($forecast['days'], 'temp_min')) : null;
    $weekRainDays = !empty($forecast['days']) ? count(array_filter($forecast['days'], fn ($d) => $d['rain_probability'] >= 40)) : 0;
@endphp

{{-- ── HERO ────────────────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden" style="background: {{ $heroGradient }}">
    {{-- Decorative coastline silhouette (pure CSS) --}}
    <div class="absolute inset-x-0 bottom-0 h-32 opacity-20" style="background: radial-gradient(ellipse at bottom, rgba(255,255,255,0.4), transparent 70%);"></div>

    <div class="relative max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16">
        <nav class="flex items-center gap-2 text-white/70 text-sm font-medium mb-8" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-white">Weather</span>
        </nav>

        <div class="grid lg:grid-cols-[1fr_auto] gap-10 items-end">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 text-white px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-5">
                    <span class="material-symbols-outlined text-[16px]">cloud</span> Live Forecast
                </div>
                <h1 class="text-white font-serif font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight mb-4">
                    Alibaug Weather <span class="text-[#f5c842]">&amp; Forecast</span>
                </h1>
                <p class="text-white/85 text-lg md:text-xl font-medium max-w-2xl">
                    Current conditions, 7-day outlook, and the best months to visit Alibaug — updated continuously throughout the day.
                </p>
            </div>

            {{-- Now-Card --}}
            <div class="bg-white/12 backdrop-blur-xl border border-white/20 rounded-3xl p-7 shadow-2xl w-full lg:min-w-[340px]">
                <p class="text-white/70 text-[11px] uppercase tracking-[0.18em] font-bold mb-1">Right Now in Alibaug</p>
                <p class="text-white/85 text-xs font-medium mb-5">Updated {{ $current['updated_at'] }}</p>

                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-white text-[88px] leading-none" style="font-variation-settings:'FILL' 1">{{ $current['icon'] }}</span>
                    <div>
                        <div class="flex items-start">
                            <span class="text-white font-bold text-7xl leading-none tabular-nums">{{ $current['temperature'] }}</span>
                            <span class="text-white/80 text-2xl font-medium mt-2">°C</span>
                        </div>
                        <p class="text-white text-xl font-semibold mt-1">{{ $current['condition'] }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mt-6 pt-5 border-t border-white/15">
                    <div>
                        <p class="text-white/60 text-[10px] uppercase tracking-wider font-bold mb-1">Feels</p>
                        <p class="text-white font-bold text-base tabular-nums">{{ $current['feels_like'] }}°</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-[10px] uppercase tracking-wider font-bold mb-1">Humidity</p>
                        <p class="text-white font-bold text-base tabular-nums">{{ $current['humidity'] }}%</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-[10px] uppercase tracking-wider font-bold mb-1">Wind</p>
                        <p class="text-white font-bold text-base tabular-nums">{{ $current['wind_speed'] }} <span class="text-xs font-medium text-white/70">km/h {{ $current['wind_direction'] }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── 7-DAY FORECAST ──────────────────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="flex items-end justify-between mb-6">
        <div>
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-2">7-Day Outlook</p>
            <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight">This week in Alibaug</h2>
        </div>
        @if ($weekHigh && $weekLow)
            <div class="hidden md:flex items-center gap-6 text-sm">
                <div>
                    <p class="text-text-secondary text-[11px] uppercase tracking-wider font-bold">Week high</p>
                    <p class="text-slate-900 font-bold text-lg tabular-nums">{{ $weekHigh }}°C</p>
                </div>
                <div>
                    <p class="text-text-secondary text-[11px] uppercase tracking-wider font-bold">Week low</p>
                    <p class="text-slate-900 font-bold text-lg tabular-nums">{{ $weekLow }}°C</p>
                </div>
                <div>
                    <p class="text-text-secondary text-[11px] uppercase tracking-wider font-bold">Wet days</p>
                    <p class="text-slate-900 font-bold text-lg tabular-nums">{{ $weekRainDays }} / 7</p>
                </div>
            </div>
        @endif
    </div>

    @if ($forecast['fallback'])
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-xl px-4 py-3 mb-6 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined">info</span>
            Live forecast is temporarily unavailable. Showing seasonal averages.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-7 gap-3">
        @foreach ($forecast['days'] as $i => $day)
            @php
                $isFirst = $i === 0;
                $bg = $isFirst ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20' : 'bg-white text-slate-900 border-border-light hover:border-primary/30 hover:-translate-y-0.5';
                $sub = $isFirst ? 'text-white/70' : 'text-text-secondary';
                $accent = $isFirst ? 'text-white' : 'text-slate-900';
                $rainColor = $isFirst ? 'text-white/85' : ($day['rain_probability'] >= 60 ? 'text-blue-600' : ($day['rain_probability'] >= 30 ? 'text-amber-600' : 'text-slate-400'));
            @endphp
            <article class="rounded-2xl border p-4 transition-all duration-200 {{ $bg }}">
                <p class="text-[11px] uppercase tracking-wider font-bold {{ $sub }} mb-2">{{ $day['day_label'] }}</p>
                <span class="material-symbols-outlined {{ $accent }} text-[36px] leading-none mb-2 block" style="font-variation-settings:'FILL' 1">{{ $day['icon'] }}</span>
                <p class="text-xs font-semibold {{ $sub }} mb-3 truncate">{{ $day['condition'] }}</p>
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="font-bold text-2xl tabular-nums {{ $accent }}">{{ $day['temp_max'] }}°</span>
                    <span class="text-sm font-medium tabular-nums {{ $sub }}">{{ $day['temp_min'] }}°</span>
                </div>
                <div class="flex items-center gap-1.5 text-xs font-semibold {{ $rainColor }}">
                    <span class="material-symbols-outlined text-[14px]">water_drop</span>
                    <span class="tabular-nums">{{ $day['rain_probability'] }}%</span>
                </div>
            </article>
        @endforeach
    </div>

    @if (!empty($forecast['days'][0]))
        @php $today = $forecast['days'][0]; @endphp
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white border border-border-light rounded-xl px-4 py-3">
                <p class="text-text-secondary text-[10px] uppercase tracking-wider font-bold mb-1">Sunrise</p>
                <p class="text-slate-900 font-bold text-base tabular-nums flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-amber-500 text-[18px]">wb_twilight</span>
                    {{ $today['sunrise'] }}
                </p>
            </div>
            <div class="bg-white border border-border-light rounded-xl px-4 py-3">
                <p class="text-text-secondary text-[10px] uppercase tracking-wider font-bold mb-1">Sunset</p>
                <p class="text-slate-900 font-bold text-base tabular-nums flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-orange-500 text-[18px]">wb_twilight</span>
                    {{ $today['sunset'] }}
                </p>
            </div>
            <div class="bg-white border border-border-light rounded-xl px-4 py-3">
                <p class="text-text-secondary text-[10px] uppercase tracking-wider font-bold mb-1">UV Index</p>
                <p class="text-slate-900 font-bold text-base tabular-nums flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-yellow-500 text-[18px]">sunny</span>
                    {{ $today['uv_index'] }} / 11
                </p>
            </div>
            <div class="bg-white border border-border-light rounded-xl px-4 py-3">
                <p class="text-text-secondary text-[10px] uppercase tracking-wider font-bold mb-1">Rainfall (today)</p>
                <p class="text-slate-900 font-bold text-base tabular-nums flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">rainy</span>
                    {{ $today['precipitation_mm'] }} mm
                </p>
            </div>
        </div>
    @endif
</section>

{{-- ── BEST TIME TO VISIT ──────────────────────────────────────────────── --}}
<section class="bg-white border-y border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3">Plan Your Visit</p>
            <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-5xl tracking-tight leading-tight mb-4">Best Time to Visit Alibaug</h2>
            <p class="text-slate-600 text-lg leading-relaxed">
                Alibaug is a year-round coastal destination. Each season offers a different vibe — from peak winter weekends to dramatic monsoon retreats. Here's what to expect month by month.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            @foreach ($seasons as $season)
                @php
                    $colorMap = [
                        'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'pill_bg' => 'bg-emerald-100', 'pill_text' => 'text-emerald-700', 'icon_bg' => 'bg-emerald-500'],
                        'amber'   => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'pill_bg' => 'bg-amber-100',   'pill_text' => 'text-amber-800',  'icon_bg' => 'bg-amber-500'],
                        'sky'     => ['bg' => 'bg-sky-50',     'border' => 'border-sky-200',     'pill_bg' => 'bg-sky-100',     'pill_text' => 'text-sky-700',    'icon_bg' => 'bg-sky-500'],
                    ];
                    $c = $colorMap[$season['rating_color']] ?? $colorMap['emerald'];
                @endphp
                <article class="{{ $c['bg'] }} border {{ $c['border'] }} rounded-2xl p-6 transition-all hover:shadow-md">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl {{ $c['icon_bg'] }} flex items-center justify-center flex-shrink-0 shadow-md">
                            <span class="material-symbols-outlined text-white text-[24px]" style="font-variation-settings:'FILL' 1">{{ $season['icon'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-slate-900 font-bold text-xl">{{ $season['season'] }}</h3>
                                <span class="{{ $c['pill_bg'] }} {{ $c['pill_text'] }} text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full">{{ $season['rating'] }}</span>
                            </div>
                            <p class="text-text-secondary text-sm font-medium">{{ $season['months'] }} · {{ $season['temp_range'] }}</p>
                        </div>
                    </div>
                    <p class="text-slate-700 text-sm leading-relaxed mb-4">{{ $season['description'] }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($season['best_for'] as $activity)
                            <span class="bg-white border border-slate-200 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full">{{ $activity }}</span>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ── FAQ / Long-form SEO content ─────────────────────────────────────── --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
    <div class="grid lg:grid-cols-[1fr_360px] gap-12">
        <div>
            <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3">Frequently Asked</p>
            <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-8">About the weather in Alibaug</h2>

            <div class="space-y-3" x-data="{ open: 0 }">
                @php
                    $faqs = [
                        [
                            'q' => 'What is the best month to visit Alibaug?',
                            'a' => 'November through February is the peak season. The weather is dry, breezy, and pleasant — perfect for villa stays, beaches, watersports, and outdoor dining. Book accommodations at least 2–3 weeks in advance for weekend visits during this window.',
                        ],
                        [
                            'q' => 'Is Alibaug worth visiting during the monsoon?',
                            'a' => 'Yes — if you want greenery, dramatic landscapes, and lower rates. The sea turns rough and most watersports pause, but the moody atmosphere is ideal for a quiet villa weekend, indoor dining, and photography. Heaviest rainfall is typically July and August.',
                        ],
                        [
                            'q' => 'How hot does Alibaug get in summer?',
                            'a' => 'Summer (March to May) sees daytime temperatures around 32–36°C with high humidity, but the coastal breeze keeps it more tolerable than inland Mumbai or Pune. Early mornings and evenings are ideal for outdoor activity.',
                        ],
                        [
                            'q' => 'Does it rain during winter in Alibaug?',
                            'a' => 'Very rarely. Winter (November to February) is the driest and most predictable season, with negligible rainfall. Occasional unseasonal showers can occur, but the week is almost always dry.',
                        ],
                        [
                            'q' => 'Is the sea safe for swimming year-round?',
                            'a' => 'No. Swimming is safest from October through May. During the monsoon (June to September), the sea is rough and lifeguards mark beaches as unsafe. Always follow flag signals and avoid swimming alone.',
                        ],
                        [
                            'q' => 'What should I pack for an Alibaug trip?',
                            'a' => 'Light cottons year-round. Add a light jacket or shawl for winter evenings. Sunscreen (SPF 50+) and a hat are essential for summer. Bring rainproof shoes and a sturdy umbrella if you visit during the monsoon.',
                        ],
                    ];
                @endphp

                @foreach ($faqs as $i => $faq)
                    <div class="bg-white border border-border-light rounded-xl overflow-hidden">
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

        {{-- Sidebar: cross-promo --}}
        <aside class="space-y-5">
            <div class="bg-gradient-to-br from-primary to-primary-dark text-white rounded-2xl p-6 shadow-lg">
                <span class="material-symbols-outlined text-[36px] mb-3 opacity-90">map</span>
                <h3 class="font-bold text-xl mb-2 leading-tight">Planning your trip?</h3>
                <p class="text-white/85 text-sm leading-relaxed mb-5">Browse handpicked villas, restaurants, and experiences across Alibaug — filtered by your dates and group size.</p>
                <a href="{{ route('search') }}" class="inline-flex items-center gap-2 bg-white text-primary font-bold text-sm px-4 py-2.5 rounded-xl hover:bg-white/95 transition-colors shadow-sm">
                    Browse Listings
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>

            <div class="bg-white border border-border-light rounded-2xl p-6">
                <span class="material-symbols-outlined text-primary text-[28px] mb-2">directions_boat</span>
                <h3 class="font-bold text-slate-900 text-lg mb-1.5 leading-tight">Getting to Alibaug</h3>
                <p class="text-text-secondary text-sm leading-relaxed mb-4">Ferry timings, road routes from Mumbai &amp; Pune, and travel tips.</p>
                <a href="{{ route('page.how-to-reach') }}" class="inline-flex items-center gap-1.5 text-primary font-bold text-sm hover:gap-2 transition-all">
                    How to Reach
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="bg-white border border-border-light rounded-2xl p-6">
                <span class="material-symbols-outlined text-amber-500 text-[28px] mb-2">storefront</span>
                <h3 class="font-bold text-slate-900 text-lg mb-1.5 leading-tight">Local Markets</h3>
                <p class="text-text-secondary text-sm leading-relaxed mb-4">Where to shop for fresh seafood, coastal produce, and Konkan specialities.</p>
                <a href="{{ route('page.local-markets') }}" class="inline-flex items-center gap-1.5 text-primary font-bold text-sm hover:gap-2 transition-all">
                    Explore Markets
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <p class="text-text-secondary text-[11px] leading-relaxed px-1">
                Weather data provided by <a href="https://open-meteo.com" target="_blank" rel="noopener" class="font-semibold underline hover:text-primary">Open-Meteo</a>. Updated every 30 minutes.
            </p>
        </aside>
    </div>
</section>

{{-- FAQ Schema for rich snippets --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {"@type":"Question","name":"What is the best month to visit Alibaug?","acceptedAnswer":{"@type":"Answer","text":"November through February is the peak season. The weather is dry, breezy, and pleasant — perfect for villa stays, beaches, watersports, and outdoor dining."}},
        {"@type":"Question","name":"Is Alibaug worth visiting during the monsoon?","acceptedAnswer":{"@type":"Answer","text":"Yes, if you want greenery, dramatic landscapes, and lower rates. The sea turns rough and most watersports pause, but the moody atmosphere is ideal for a quiet villa weekend."}},
        {"@type":"Question","name":"How hot does Alibaug get in summer?","acceptedAnswer":{"@type":"Answer","text":"Summer (March to May) sees daytime temperatures around 32–36°C with high humidity, but the coastal breeze keeps it more tolerable than inland Mumbai or Pune."}},
        {"@type":"Question","name":"Does it rain during winter in Alibaug?","acceptedAnswer":{"@type":"Answer","text":"Very rarely. Winter is the driest and most predictable season with negligible rainfall."}},
        {"@type":"Question","name":"Is the sea safe for swimming year-round?","acceptedAnswer":{"@type":"Answer","text":"No. Swimming is safest from October through May. During the monsoon, the sea is rough and lifeguards mark beaches as unsafe."}},
        {"@type":"Question","name":"What should I pack for an Alibaug trip?","acceptedAnswer":{"@type":"Answer","text":"Light cottons year-round. Add a jacket for winter evenings. Sunscreen and a hat for summer. Rainproof shoes and an umbrella for monsoon."}}
    ]
}
</script>
@endsection

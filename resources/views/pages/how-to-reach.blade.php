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

{{-- ── FERRY SCHEDULE ──────────────────────────────────────────────────── --}}
<section class="bg-white border-y border-border-light">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
        <div class="grid lg:grid-cols-[1fr_400px] gap-10 items-start">
            <div>
                <p class="text-text-secondary text-xs uppercase tracking-[0.18em] font-bold mb-3">Ferry Schedule</p>
                <h2 class="text-slate-900 font-serif font-bold text-3xl md:text-4xl tracking-tight mb-4">Mumbai to Mandwa ferry timings</h2>
                <p class="text-slate-600 text-base leading-relaxed mb-6">
                    Six operators across three ferry classes run scheduled crossings between Gateway of India and Mandwa Jetty year-round (weather permitting). Mandwa is a 20-minute drive from Alibaug town.
                </p>

                {{-- Operator cards --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white p-4">
                            <span class="material-symbols-outlined text-[26px] mb-1" style="font-variation-settings:'FILL' 1">directions_car</span>
                            <h3 class="font-bold text-base leading-tight">M2M / RoRo Ferry</h3>
                            <p class="text-white/75 text-xs">Vehicle + passenger RoRo ferry</p>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">Hours</span>
                                <span class="font-bold text-slate-900 tabular-nums">7:00 AM–7:00 PM</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">Foot passenger</span>
                                <span class="font-bold text-amber-600 tabular-nums">₹250–₹350</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">With vehicle</span>
                                <span class="font-bold text-amber-600 tabular-nums">₹1,200–₹2,500</span>
                            </div>
                            <a href="https://www.m2mferries.com/schedule" target="_blank" rel="noopener"
                               class="mt-1 flex items-center justify-center gap-1 w-full bg-amber-50 text-amber-700 font-bold text-xs py-2 rounded-lg hover:bg-amber-100 transition-colors">
                                Book tickets <span class="material-symbols-outlined text-[14px]">arrow_outward</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-4">
                            <span class="material-symbols-outlined text-[26px] mb-1" style="font-variation-settings:'FILL' 1">directions_boat</span>
                            <h3 class="font-bold text-base leading-tight">Standard Ferry</h3>
                            <p class="text-white/75 text-xs">PNP · Ajanta · Maldar · Apollo</p>
                        </div>
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">Hours</span>
                                <span class="font-bold text-slate-900 tabular-nums">7:10 AM–8:15 PM</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">Price</span>
                                <span class="font-bold text-emerald-600 tabular-nums">₹150–₹200</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs pt-1">
                                <a href="http://www.MyBoatRide.com" target="_blank" rel="noopener" class="text-emerald-700 font-semibold underline hover:text-emerald-800">Book PNP</a>
                                <a href="http://www.maldarcatamaran.com" target="_blank" rel="noopener" class="text-emerald-700 font-semibold underline hover:text-emerald-800">Book Maldar</a>
                            </div>
                            <a href="#full-timetable"
                               class="mt-1 flex items-center justify-center gap-1 w-full bg-emerald-50 text-emerald-700 font-bold text-xs py-2 rounded-lg hover:bg-emerald-100 transition-colors">
                                See full timetable <span class="material-symbols-outlined text-[14px]">expand_more</span>
                            </a>
                        </div>
                    </div>
                </div>
                <p class="text-text-secondary text-xs mt-3 leading-relaxed">
                    * Schedules vary on weekends and during monsoon (June–September). Always confirm via the operator's website or call ahead.
                </p>

                {{-- Full departure timetable --}}
                @php
                    $operatorColors = ['AJANTA' => '#2F9E68', 'APOLLO' => '#E2603A', 'MALDAR' => '#7B5FC4', 'PNP' => '#2E6FE0'];
                    $operatorNames  = ['AJANTA' => 'Ajanta', 'APOLLO' => 'Apollo', 'MALDAR' => 'Maldar', 'PNP' => 'PNP'];
                    $operatorBookingUrls = ['PNP' => 'http://www.MyBoatRide.com', 'MALDAR' => 'http://www.maldarcatamaran.com'];

                    $rawToMandwa = [
                        ['6:15 AM','AJANTA'], ['7:00 AM','AJANTA'], ['7:45 AM','APOLLO'], ['8:15 AM','PNP'],
                        ['8:30 AM','MALDAR'], ['9:15 AM','AJANTA'], ['10:00 AM','AJANTA'], ['10:15 AM','PNP'],
                        ['10:45 AM','MALDAR'], ['11:15 AM','AJANTA'], ['12:00 PM','AJANTA'], ['12:15 PM','PNP'],
                        ['12:45 PM','APOLLO'], ['1:15 PM','MALDAR'], ['2:00 PM','AJANTA'], ['2:15 PM','PNP'],
                        ['3:00 PM','AJANTA'], ['3:30 PM','MALDAR'], ['4:00 PM','AJANTA'], ['4:15 PM','PNP'],
                        ['4:30 PM','AJANTA'], ['5:00 PM','AJANTA'], ['5:30 PM','APOLLO'], ['6:00 PM','MALDAR'],
                        ['6:30 PM','PNP'], ['7:00 PM','APOLLO'], ['8:15 PM','PNP'],
                    ];
                    $rawToGateway = [
                        ['7:10 AM','PNP'], ['7:30 AM','AJANTA'], ['8:15 AM','AJANTA'], ['8:45 AM','APOLLO'],
                        ['9:05 AM','PNP'], ['9:30 AM','MALDAR'], ['10:30 AM','AJANTA'], ['11:05 AM','PNP'],
                        ['11:15 AM','AJANTA'], ['11:45 AM','MALDAR'], ['12:30 PM','AJANTA'], ['1:05 PM','PNP'],
                        ['1:15 PM','AJANTA'], ['1:45 PM','APOLLO'], ['2:15 PM','MALDAR'], ['3:05 PM','PNP'],
                        ['3:15 PM','AJANTA'], ['4:15 PM','AJANTA'], ['4:30 PM','MALDAR'], ['5:05 PM','PNP'],
                        ['5:15 PM','AJANTA'], ['5:45 PM','AJANTA'], ['6:15 PM','AJANTA'], ['6:30 PM','APOLLO'],
                        ['7:00 PM','MALDAR'], ['7:30 PM','PNP'], ['8:00 PM','APOLLO'],
                    ];

                    $toMinutes = function (string $time): int {
                        [$hm, $suffix] = explode(' ', $time);
                        [$h, $m] = array_map('intval', explode(':', $hm));
                        if ($suffix === 'PM' && $h !== 12) { $h += 12; }
                        if ($suffix === 'AM' && $h === 12) { $h = 0; }
                        return $h * 60 + $m;
                    };

                    $nowIst = now('Asia/Kolkata');
                    $nowMinutes = $nowIst->hour * 60 + $nowIst->minute;

                    $buildSchedule = function (array $raw) use ($toMinutes, $nowMinutes, $operatorColors, $operatorNames) {
                        $nextIndex = null;
                        foreach ($raw as $i => $entry) {
                            if ($toMinutes($entry[0]) > $nowMinutes) { $nextIndex = $i; break; }
                        }
                        $rows = [];
                        foreach ($raw as $i => $entry) {
                            [$time, $op] = $entry;
                            $rows[] = [
                                'time' => $time,
                                'opName' => $operatorNames[$op],
                                'color' => $operatorColors[$op],
                                'isNext' => $i === $nextIndex,
                                'isPast' => $toMinutes($time) <= $nowMinutes,
                            ];
                        }
                        return [
                            'rows' => $rows,
                            'next' => $nextIndex !== null ? $raw[$nextIndex] : null,
                            'nextIn' => $nextIndex !== null ? $toMinutes($raw[$nextIndex][0]) - $nowMinutes : null,
                            'firstTime' => $raw[0][0],
                        ];
                    };

                    $scheduleToMandwa = $buildSchedule($rawToMandwa);
                    $scheduleToGateway = $buildSchedule($rawToGateway);
                @endphp
                <div class="mt-10" id="full-timetable" x-data="{ dir: 'toMandwa' }">
                    <div class="flex items-center gap-2.5 mb-1">
                        <span class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
                        </span>
                        <h3 class="text-slate-900 font-serif font-bold text-xl tracking-tight">Full sailing-by-sailing timetable</h3>
                    </div>
                    <p class="text-text-secondary text-sm mb-4">Every scheduled crossing for the four standard open-deck operators. M2M/RoRo (above) runs to its own separate schedule.</p>

                    <div class="inline-flex bg-slate-100 rounded-xl p-1 mb-4 gap-1">
                        <button type="button" @click="dir = 'toMandwa'"
                                :class="dir === 'toMandwa' ? 'bg-primary text-white shadow-md' : 'text-text-secondary hover:text-slate-900'"
                                class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-bold transition-all">
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span> Gateway → Mandwa
                        </button>
                        <button type="button" @click="dir = 'toGateway'"
                                :class="dir === 'toGateway' ? 'bg-primary text-white shadow-md' : 'text-text-secondary hover:text-slate-900'"
                                class="flex items-center gap-1.5 px-4 py-2.5 rounded-lg text-sm font-bold transition-all">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Mandwa → Gateway
                        </button>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        @foreach ($operatorNames as $code => $name)
                            @if (isset($operatorBookingUrls[$code]))
                                <a href="{{ $operatorBookingUrls[$code] }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-text-secondary underline decoration-dotted hover:text-slate-900">
                                    <span class="w-2 h-2 rounded-full" style="background:{{ $operatorColors[$code] }}"></span> {{ $name }}
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-text-secondary">
                                    <span class="w-2 h-2 rounded-full" style="background:{{ $operatorColors[$code] }}"></span> {{ $name }}
                                </span>
                            @endif
                        @endforeach
                    </div>

                    <div class="border border-border-light rounded-2xl overflow-hidden shadow-sm">
                        @foreach (['toMandwa' => $scheduleToMandwa, 'toGateway' => $scheduleToGateway] as $key => $schedule)
                            <div x-show="dir === '{{ $key }}'" @if($key !== 'toMandwa') style="display: none;" @endif>
                                @if ($schedule['next'])
                                    <div class="flex items-center gap-4 px-5 py-4 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent border-b border-primary/15">
                                        <div class="text-2xl font-bold text-primary tabular-nums leading-none">{{ $schedule['next'][0] }}</div>
                                        <div>
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-primary/70">Next sailing</p>
                                            <p class="text-sm font-semibold text-slate-800">{{ $operatorNames[$schedule['next'][1]] }} · departs in {{ $schedule['nextIn'] }} min</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="px-5 py-4 bg-slate-50 border-b border-border-light text-sm text-text-secondary">
                                        Service has closed for today — first boat resumes at {{ $schedule['firstTime'] }}.
                                    </div>
                                @endif
                                <div class="max-h-[380px] overflow-y-auto divide-y divide-border-light">
                                    @foreach ($schedule['rows'] as $row)
                                        <div class="grid grid-cols-[80px_1fr_64px] items-center gap-2 px-5 py-2.5 {{ $row['isPast'] ? 'opacity-40' : '' }}"
                                             style="{{ $row['isNext'] ? 'background:'.$row['color'].'0D;' : '' }} border-left:3px solid {{ $row['isNext'] ? $row['color'] : 'transparent' }};">
                                            <span class="font-bold text-slate-900 tabular-nums text-sm">{{ $row['time'] }}</span>
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full w-fit" style="background:{{ $row['color'] }}1A; color:{{ $row['color'] }};">
                                                {{ $row['opName'] }}
                                            </span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-right {{ $row['isNext'] ? 'text-primary' : 'text-slate-300' }}">
                                                {{ $row['isNext'] ? 'Next' : ($row['isPast'] ? 'Sailed' : '') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-text-secondary text-xs mt-3 leading-relaxed">
                        Source: Uniland Estates ferry timetable. Confirm the day's schedule directly with the operator — sailings shift with tide, weather, and monsoon closures.
                    </p>
                </div>
            </div>

            {{-- Side info --}}
            <aside class="space-y-4">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-5">
                    <span class="material-symbols-outlined text-amber-600 text-[28px] mb-2">warning</span>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">Monsoon advisory</h3>
                    <p class="text-slate-700 text-xs leading-relaxed">Ferries are frequently cancelled June through September due to rough seas. Always confirm operation status before heading to Gateway of India.</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-5">
                    <span class="material-symbols-outlined text-emerald-600 text-[28px] mb-2">tips_and_updates</span>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">Skip the queue</h3>
                    <p class="text-slate-700 text-xs leading-relaxed">Book <a href="https://www.m2mferries.com/schedule" target="_blank" rel="noopener" class="text-emerald-700 font-semibold underline">M2M/RoRo</a> tickets online via their app, or the standard ferries directly with PNP or Maldar. Walk-ins are accepted at the jetty but weekends and holidays can see 30–45 minute waits.</p>
                </div>

                <div class="bg-gradient-to-br from-sky-50 to-blue-50 border border-sky-200 rounded-2xl p-5">
                    <span class="material-symbols-outlined text-sky-600 text-[28px] mb-2">cloud</span>
                    <h3 class="font-bold text-slate-900 text-base mb-1.5">Check the weather first</h3>
                    <p class="text-slate-700 text-xs leading-relaxed mb-3">Sea conditions matter for ferry rides. Use our live forecast to plan around rain and rough days.</p>
                    <a href="{{ route('weather.index') }}" class="inline-flex items-center gap-1.5 text-sky-700 font-bold text-xs hover:gap-2 transition-all">
                        View weather <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
            </aside>
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

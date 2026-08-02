{{--
    Mumbai ↔ Mandwa ferry schedule.
    Shared by the dedicated /ferry-schedule page and previously inlined in
    the how-to-reach guide. Kept in one place so the timetable is only ever
    updated once.
--}}
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

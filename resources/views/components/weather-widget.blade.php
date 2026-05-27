@php
    $current = $forecast['current'];
    $isDay = $current['is_day'];
    $bgGradient = $isDay
        ? 'linear-gradient(135deg, #0b3d91 0%, #1f5fc7 60%, #4a8de0 100%)'
        : 'linear-gradient(135deg, #0a1f4d 0%, #1a2f5e 60%, #2d4373 100%)';
@endphp

{{-- ── HERO VARIANT ── Compact glass card for the home hero ───────────── --}}
@if ($variant === 'hero')
    <a href="{{ route('weather.index') }}"
       class="group block backdrop-blur-xl bg-white/10 hover:bg-white/15 border border-white/20 rounded-2xl px-4 py-3 transition-all duration-300 shadow-lg hover:shadow-xl"
       aria-label="View 7-day Alibaug weather forecast">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-white text-[26px]" style="font-variation-settings:'FILL' 1">{{ $current['icon'] }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-baseline gap-2">
                    <span class="text-white font-bold text-2xl leading-none tabular-nums">{{ $current['temperature'] }}°</span>
                    <span class="text-white/70 text-xs font-medium uppercase tracking-wider">Alibaug</span>
                </div>
                <p class="text-white/80 text-xs font-medium mt-0.5 truncate">{{ $current['condition'] }} · feels {{ $current['feels_like'] }}°</p>
            </div>
            <span class="material-symbols-outlined text-white/50 text-[18px] group-hover:translate-x-0.5 group-hover:text-white transition-all">arrow_forward</span>
        </div>
    </a>

{{-- ── INLINE VARIANT ── Slim row for listing sidebar ──────────────────── --}}
@elseif ($variant === 'inline')
    <a href="{{ route('weather.index') }}"
       class="group flex items-center gap-3 bg-white border border-border-light hover:border-primary/40 rounded-xl px-4 py-3 transition-all">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $bgGradient }}">
            <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">{{ $current['icon'] }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[11px] uppercase tracking-wider text-text-secondary font-semibold mb-0.5">Alibaug Weather</p>
            <div class="flex items-baseline gap-1.5">
                <span class="text-slate-900 font-bold text-lg tabular-nums leading-none">{{ $current['temperature'] }}°C</span>
                <span class="text-slate-500 text-xs font-medium">· {{ $current['condition'] }}</span>
            </div>
        </div>
        <span class="material-symbols-outlined text-slate-400 text-[18px] group-hover:translate-x-0.5 group-hover:text-primary transition-all">chevron_right</span>
    </a>

{{-- ── COMPACT VARIANT ── Standalone card (default) ────────────────────── --}}
@else
    <div class="rounded-2xl overflow-hidden shadow-xl" style="background: {{ $bgGradient }}">
        {{-- Top: current conditions --}}
        <div class="px-6 py-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-white/70 text-[11px] uppercase tracking-[0.15em] font-semibold mb-1">{{ $forecast['location'] }}</p>
                    <p class="text-white/90 text-xs font-medium">Updated {{ $current['updated_at'] }}</p>
                </div>
                <a href="{{ route('weather.index') }}"
                   class="text-white/70 hover:text-white text-[11px] font-bold uppercase tracking-wider flex items-center gap-1 transition-colors">
                    7-day
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="flex items-center gap-4">
                <span class="material-symbols-outlined text-white text-[68px] leading-none" style="font-variation-settings:'FILL' 1">{{ $current['icon'] }}</span>
                <div>
                    <div class="flex items-start">
                        <span class="text-white font-bold text-6xl leading-none tabular-nums">{{ $current['temperature'] }}</span>
                        <span class="text-white/80 text-xl font-medium mt-1">°C</span>
                    </div>
                    <p class="text-white text-lg font-semibold mt-1">{{ $current['condition'] }}</p>
                    <p class="text-white/70 text-xs font-medium mt-0.5">Feels like {{ $current['feels_like'] }}° · Humidity {{ $current['humidity'] }}%</p>
                </div>
            </div>
        </div>

        {{-- Bottom: 5-day strip --}}
        @if (!empty($forecast['days']))
            <div class="bg-white/10 backdrop-blur-sm border-t border-white/15 px-2 py-3">
                <div class="grid grid-cols-5 gap-1">
                    @foreach (array_slice($forecast['days'], 0, 5) as $day)
                        <div class="text-center px-1 py-1.5 rounded-lg hover:bg-white/10 transition-colors">
                            <p class="text-white/70 text-[10px] uppercase tracking-wider font-bold mb-1.5 truncate">
                                {{ $day['day_label'] === 'Today' ? 'Today' : \Illuminate\Support\Carbon::parse($day['date'])->format('D') }}
                            </p>
                            <span class="material-symbols-outlined text-white text-[22px] leading-none mb-1" style="font-variation-settings:'FILL' 1">{{ $day['icon'] }}</span>
                            <p class="text-white text-sm font-bold tabular-nums leading-none mt-1">{{ $day['temp_max'] }}°</p>
                            <p class="text-white/60 text-xs tabular-nums leading-none mt-0.5">{{ $day['temp_min'] }}°</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif

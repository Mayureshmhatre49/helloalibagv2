@extends('layouts.app')
@section('title', 'Events in Alibaug — What\'s On This Weekend')
@section('meta_description', 'Upcoming events in Alibaug: parties, food festivals, music nights, watersport meets, and weekend pop-ups. Updated weekly with what\'s on this weekend.')

@push('styles')
{{-- Schema.org ItemList of upcoming Events --}}
@if ($events->total() > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "itemListOrder": "https://schema.org/ItemListOrderAscending",
    "numberOfItems": {{ $events->total() }},
    "itemListElement": [
        @foreach ($events as $i => $event)
        {
            "@type": "ListItem",
            "position": {{ $i + 1 }},
            "item": {
                "@type": "Event",
                "name": @json($event->title),
                "url": @json(route('listing.show', [$event->category->slug, $event->slug])),
                "startDate": @json(optional($event->event_start_at)->toIso8601String()),
                "endDate": @json(optional($event->event_end_at)->toIso8601String()),
                "location": {
                    "@type": "Place",
                    "name": @json($event->area?->name ?: 'Alibaug'),
                    "address": { "@type": "PostalAddress", "addressLocality": @json($event->area?->name ?: 'Alibaug'), "addressRegion": "Maharashtra", "addressCountry": "IN" }
                }
            }
        }@if (!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif
@endpush

@section('content')
{{-- HERO --}}
<section class="bg-gradient-to-br from-[#0b3d91] via-[#1f5fc7] to-[#4a8de0] text-white">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-10">
        <nav class="flex items-center gap-2 text-white/70 text-sm font-medium mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            <span class="text-white">Events</span>
        </nav>

        <div class="flex flex-wrap items-end justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 text-white px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <span class="material-symbols-outlined text-[16px]">celebration</span>
                    Events Calendar
                </div>
                <h1 class="font-serif font-bold text-4xl md:text-5xl lg:text-6xl tracking-tight leading-tight mb-3">
                    What's on in <span class="text-[#f5c842]">Alibaug</span>
                </h1>
                <p class="text-white/85 text-lg leading-relaxed">Music nights, food festivals, watersports meets, weekend pop-ups — handpicked and updated continuously.</p>
            </div>

            <div class="bg-white/12 backdrop-blur-xl border border-white/20 rounded-2xl px-5 py-4 shadow-2xl">
                <p class="text-white/70 text-[11px] uppercase tracking-[0.18em] font-bold mb-1">This weekend</p>
                <p class="text-white font-bold text-xl leading-tight">{{ $weekendLabel }}</p>
                <p class="text-white/85 text-sm font-medium mt-1">{{ $counts['weekend'] }} {{ Str::plural('event', $counts['weekend']) }} planned</p>
            </div>
        </div>
    </div>
</section>

{{-- FILTER CHIPS --}}
<section class="bg-white border-b border-border-light sticky top-[64px] z-30 shadow-sm">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-3 overflow-x-auto">
        @php
            $filters = [
                ['key' => 'upcoming', 'label' => 'All upcoming', 'icon' => 'event'],
                ['key' => 'today',    'label' => 'Today',         'icon' => 'today'],
                ['key' => 'weekend',  'label' => 'This weekend',  'icon' => 'weekend'],
                ['key' => 'week',     'label' => 'Next 7 days',   'icon' => 'date_range'],
                ['key' => 'month',    'label' => 'Next 30 days',  'icon' => 'calendar_month'],
            ];
        @endphp
        <div class="flex items-center gap-2 min-w-max">
            @foreach ($filters as $f)
                @php $active = $filter === $f['key']; @endphp
                <a href="{{ route('events.calendar', ['when' => $f['key']]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold whitespace-nowrap transition-all
                          {{ $active ? 'bg-slate-900 text-white shadow-lg' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    <span class="material-symbols-outlined text-[14px]">{{ $f['icon'] }}</span>
                    {{ $f['label'] }}
                    <span class="ml-1 text-[10px] font-bold tabular-nums
                                {{ $active ? 'text-white/70' : 'text-text-secondary' }}">
                        ({{ $counts[$f['key']] }})
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- EVENT LIST --}}
<section class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
    @if ($events->isEmpty())
        <div class="bg-white border border-border-light rounded-2xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[36px]">event_busy</span>
            </div>
            <h2 class="text-slate-900 font-bold text-xl mb-2">Nothing in this window yet</h2>
            <p class="text-text-secondary text-sm max-w-md mx-auto mb-6">No events match the selected filter right now. Try a broader window or browse all upcoming events.</p>
            <a href="{{ route('events.calendar') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-primary-dark transition-colors">
                Show all upcoming <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    @else
        <div class="space-y-10">
            @foreach ($grouped as $dateKey => $dayEvents)
                @php $day = \Carbon\Carbon::parse($dateKey); @endphp
                <section>
                    <div class="flex items-baseline gap-3 mb-4">
                        <div class="bg-primary text-white text-center rounded-xl w-14 h-14 flex flex-col items-center justify-center shadow-md flex-shrink-0">
                            <span class="text-[10px] font-bold uppercase tracking-wider opacity-80">{{ $day->format('M') }}</span>
                            <span class="text-xl font-bold leading-none tabular-nums">{{ $day->format('j') }}</span>
                        </div>
                        <div>
                            <h2 class="text-slate-900 font-serif font-bold text-2xl tracking-tight leading-tight">
                                {{ $day->isToday() ? 'Today' : ($day->isTomorrow() ? 'Tomorrow' : $day->format('l')) }}
                            </h2>
                            <p class="text-text-secondary text-sm font-medium">{{ $day->format('F j, Y') }} · {{ $dayEvents->count() }} {{ Str::plural('event', $dayEvents->count()) }}</p>
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($dayEvents as $event)
                            @php
                                $image = $event->images->first();
                                $imageUrl = null;
                                if ($image) {
                                    $imageUrl = \str_starts_with($image->path, 'http')
                                        ? $image->path
                                        : asset('storage/' . ltrim(preg_replace('#^/?storage/#', '', $image->path), '/'));
                                }
                            @endphp
                            <a href="{{ route('listing.show', [$event->category->slug, $event->slug]) }}"
                               class="group bg-white border border-border-light hover:border-primary/40 hover:shadow-lg rounded-2xl overflow-hidden transition-all">
                                <div class="aspect-[16/10] bg-slate-100 overflow-hidden relative">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $event->title }}" loading="lazy"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                            <span class="material-symbols-outlined" style="font-size:40px">celebration</span>
                                        </div>
                                    @endif
                                    @if ($event->event_start_at)
                                        <div class="absolute top-3 left-3 bg-slate-900/85 backdrop-blur text-white text-[11px] font-bold px-2.5 py-1 rounded-full inline-flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ $event->event_start_at->setTimezone('Asia/Kolkata')->format('g:i A') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    <h3 class="text-slate-900 font-bold text-lg leading-tight mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $event->title }}</h3>
                                    <div class="flex items-center gap-2 text-xs text-text-secondary font-medium mb-2">
                                        @if ($event->area)
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                {{ $event->area->name }}
                                            </span>
                                        @endif
                                        @if ($event->price)
                                            <span class="text-slate-300">·</span>
                                            <span class="text-slate-900 font-bold tabular-nums">₹{{ number_format((float) $event->price, 0) }}</span>
                                        @endif
                                    </div>
                                    @if ($event->description)
                                        <p class="text-slate-600 text-sm leading-snug line-clamp-2">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <div class="mt-12">{{ $events->links() }}</div>
    @endif
</section>

{{-- Suggest your event CTA --}}
<section class="bg-gradient-to-br from-amber-50 to-orange-50 border-y border-amber-100">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 text-center">
        <h2 class="text-slate-900 font-serif font-bold text-2xl md:text-3xl mb-2 tracking-tight">Organising an event in Alibaug?</h2>
        <p class="text-slate-700 max-w-xl mx-auto mb-6 text-base leading-relaxed">List your weekend party, food festival, retreat, or pop-up — free, and reaches the audience already planning their Alibaug trip.</p>
        <a href="{{ route('owner.onboarding.start') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm px-5 py-3 rounded-xl shadow-lg shadow-amber-500/30 transition-colors">
            List your event <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>
</section>
@endsection

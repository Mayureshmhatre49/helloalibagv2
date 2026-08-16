@extends('layouts.app')
@section('title', 'Mumbai to Alibaug Ferry Timings 2026 — Full Mandwa Schedule')
@section('meta_description', 'Complete Mumbai to Alibaug ferry timetable — every sailing from Gateway of India to Mandwa Jetty and back, for M2M RoRo, PNP, Ajanta, Maldar and Apollo. Booking links, fares guidance and monsoon advisory.')

@section('jsonld')
@include('partials.schema.howto')
@include('partials.schema.faq', ['faqs' => [
    ['question' => 'How long does the Mumbai to Alibaug ferry take?', 'answer' => 'The crossing from Gateway of India to Mandwa Jetty takes about 60 minutes, plus roughly 20–30 minutes by road from Mandwa to Alibaug town.'],
    ['question' => 'Do ferries to Alibaug run during the monsoon?', 'answer' => 'Ferries are frequently cancelled between June and September because of rough seas. Always confirm the day\'s sailings directly with the operator before travelling.'],
    ['question' => 'Can I take my car on the ferry to Alibaug?', 'answer' => 'Yes. The M2M RoRo service carries vehicles as well as passengers between Bhaucha Dhakka and Mandwa.'],
    ['question' => 'What is the last ferry back to Mumbai?', 'answer' => 'The last open-deck passenger ferry usually departs Mandwa Jetty around 7:30 PM to 8:15 PM depending on the operator.'],
]])
@include('partials.schema.breadcrumbs', ['crumbs' => [
    ['label' => 'Home', 'url' => route('home')],
    ['label' => 'Ferry Timings', 'url' => route('page.ferry-schedule')],
]])
@endsection

@section('content')
<main class="bg-slate-50">

    {{-- Hero --}}
    <div class="bg-white border-b border-slate-200 pt-16 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="material-symbols-outlined text-[18px]">directions_boat</span> Ferry Timetable
            </div>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">Mumbai&nbsp;↔&nbsp;Alibaug Ferry Timings</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Every scheduled crossing between Gateway of India and Mandwa Jetty, for all operators. The sea route takes about an hour and avoids the 95&nbsp;km drive around the bay.</p>
        </div>
    </div>

    {{-- Quick facts --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 mb-4 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach ([
                ['schedule', 'Crossing time', '~60 minutes'],
                ['directions_car', 'Mandwa → Alibaug', '20–30 min by road'],
                ['groups', 'Operators', 'M2M, PNP, Ajanta, Maldar, Apollo'],
                ['warning', 'Monsoon', 'Often suspended Jun–Sep'],
            ] as [$icon, $label, $value])
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                    <span class="material-symbols-outlined text-primary text-[20px] mb-1.5 block">{{ $icon }}</span>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">{{ $label }}</p>
                    <p class="text-sm font-bold text-slate-900 leading-snug">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- The shared timetable --}}
    @include('partials._ferry-schedule')

    {{-- Cross-links --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Planning the rest of the trip</h2>
            <div class="grid sm:grid-cols-3 gap-3">
                <a href="{{ route('page.how-to-reach') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">alt_route</span>
                    <span class="text-sm font-semibold text-slate-800">All routes — road &amp; train</span>
                </a>
                <a href="{{ route('page.beaches') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">beach_access</span>
                    <span class="text-sm font-semibold text-slate-800">Which beach to visit</span>
                </a>
                @if(app(\App\Services\MapApiService::class)->isEnabled())
                <a href="{{ route('map.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-slate-200 hover:border-primary hover:bg-primary/5 transition-colors">
                    <span class="material-symbols-outlined text-primary">map</span>
                    <span class="text-sm font-semibold text-slate-800">Explore the map</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

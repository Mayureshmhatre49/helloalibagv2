@extends('layouts.app')
@section('title', 'How to Reach Alibaug from Mumbai — Hello Alibaug')
@section('meta_desc', 'Complete guide on how to reach Alibaug from Mumbai, Pune, and Thane by ferry, road, or bus. Includes estimated travel time, cost, tips, and Google Maps directions.')

@section('content')
<main class="bg-slate-50 min-h-screen">
    <div class="bg-white border-b border-slate-200 pt-16 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="material-symbols-outlined text-[18px]">route</span> Travel Guide
            </div>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">How to Reach Alibaug</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Mumbai to Alibaug distance, travel time, cost comparison — ferry vs road. Everything you need to plan your trip.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Route Comparison Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            {{-- Ferry Route --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-primary text-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-3xl">directions_boat</span>
                        <div>
                            <h2 class="text-xl font-bold">By Ferry</h2>
                            <p class="text-primary-light text-sm opacity-80">Gateway of India → Mandwa Jetty</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Travel Time</span>
                        <span class="font-bold text-slate-900">~1 hour (ferry) + 30 min (road to Alibaug)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Ferry Cost</span>
                        <span class="font-bold text-slate-900">₹150–₹280 per person</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Car Ferry</span>
                        <span class="font-bold text-slate-900">₹1,200–₹2,500 (Ro-Ro)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Frequency</span>
                        <span class="font-bold text-slate-900">Every 30–60 min (peak)</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500">Best For</span>
                        <span class="font-bold text-emerald-600">Fastest, scenic, no traffic</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 text-xs text-blue-700 mt-2">
                        <strong>Tip:</strong> Book RIMT/M2M ferry tickets online during weekends. Last ferry departs around 5:30–6:00 PM from Mandwa.
                    </div>
                </div>
            </div>

            {{-- Road Route --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-slate-800 text-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-3xl">directions_car</span>
                        <div>
                            <h2 class="text-xl font-bold">By Road</h2>
                            <p class="text-slate-400 text-sm">Mumbai → NH66 → Pen → Alibaug</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Distance</span>
                        <span class="font-bold text-slate-900">~95 km (via Panvel)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Travel Time</span>
                        <span class="font-bold text-slate-900">2.5–4 hours (traffic dependent)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Toll Charges</span>
                        <span class="font-bold text-slate-900">₹150–₹250 (approx)</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-500">Fuel Cost (Est.)</span>
                        <span class="font-bold text-slate-900">₹600–₹900 (one way)</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500">Best For</span>
                        <span class="font-bold text-amber-600">Large groups, luggage, flexible timing</span>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 text-xs text-amber-700 mt-2">
                        <strong>Tip:</strong> Friday evening traffic from Mumbai can push travel to 5+ hours. Leave before 2 PM or after 10 PM.
                    </div>
                </div>
            </div>
        </div>

        {{-- From Other Cities --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-8 mb-12">
            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">pin_drop</span> From Other Cities
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-50 rounded-xl p-5">
                    <h3 class="font-bold text-slate-900 mb-2">From Pune</h3>
                    <p class="text-sm text-slate-600 mb-1">Distance: ~145 km</p>
                    <p class="text-sm text-slate-600 mb-1">Time: 3–4 hours via NH66</p>
                    <p class="text-xs text-slate-500">Route: Pune → Expressway → Khopoli → Pen → Alibaug</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-5">
                    <h3 class="font-bold text-slate-900 mb-2">From Thane</h3>
                    <p class="text-sm text-slate-600 mb-1">Distance: ~85 km</p>
                    <p class="text-sm text-slate-600 mb-1">Time: 2–3 hours</p>
                    <p class="text-xs text-slate-500">Route: Thane → Panvel → NH66 → Pen → Alibaug</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-5">
                    <h3 class="font-bold text-slate-900 mb-2">From Navi Mumbai</h3>
                    <p class="text-sm text-slate-600 mb-1">Distance: ~70 km</p>
                    <p class="text-sm text-slate-600 mb-1">Time: 1.5–2.5 hours</p>
                    <p class="text-xs text-slate-500">Route: Belapur → Panvel → NH66 → Pen → Alibaug</p>
                </div>
            </div>
        </div>

        {{-- Google Maps Embed --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-12">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">map</span> Map: Mumbai to Alibaug
                </h2>
            </div>
            <div class="aspect-[16/9]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d241317.8291!2d72.7!3d18.9!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x3be7c6306644edc1%3A0x5da4ed8f8d648c69!2sMumbai!3m2!1d19.0760!2d72.8777!4m5!1s0x3be873f4e3cfd80d%3A0x1cd7a2529d205e0!2sAlibaug!3m2!1d18.6414!2d72.8722!5e0!3m2!1sen!2sin!4v1709900000000!5m2!1sen!2sin"
                        class="w-full h-full border-0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        {{-- MSRTC Bus --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-violet-500">directions_bus</span> By Public Bus (MSRTC)
            </h2>
            <div class="text-sm text-slate-600 space-y-2">
                <p>MSRTC operates regular buses from <strong>Mumbai Central</strong> and <strong>Panvel</strong> to Alibaug.</p>
                <p>Travel Time: <strong>3–4 hours</strong> | Cost: <strong>₹150–₹300</strong> depending on bus type.</p>
                <p>Book online at <a href="https://msrtc.maharashtra.gov.in" target="_blank" class="text-primary font-bold hover:underline">msrtc.maharashtra.gov.in</a></p>
            </div>
        </div>
    </div>
</main>
@endsection

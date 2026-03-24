@extends('layouts.app')
@section('title', 'Local Markets & Bazaars in Alibaug — Hello Alibaug')
@section('meta_desc', 'Weekly market schedule for Alibaug — find local fresh produce markets, fish markets, flea markets, and organic farm stands with timings and locations.')

@section('content')
<main class="bg-slate-50 min-h-screen">
    <div class="bg-white border-b border-slate-200 pt-16 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="material-symbols-outlined text-[18px]">storefront</span> Weekly Schedule
            </div>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">Local Markets & Bazaars</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Your complete guide to weekly markets, fish markets, and bazaars across Alibaug and surrounding areas.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Market Schedule Table --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="text-left px-6 py-4 font-bold text-slate-700">Market</th>
                            <th class="text-left px-6 py-4 font-bold text-slate-700">Day</th>
                            <th class="text-left px-6 py-4 font-bold text-slate-700">Timings</th>
                            <th class="text-left px-6 py-4 font-bold text-slate-700">Location</th>
                            <th class="text-left px-6 py-4 font-bold text-slate-700">What to Buy</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Alibaug Main Market (Thursday Bazaar)</td>
                            <td class="px-6 py-4"><span class="bg-primary/10 text-primary px-2 py-1 rounded-md text-xs font-bold">Thursday</span></td>
                            <td class="px-6 py-4 text-slate-600">7:00 AM — 1:00 PM</td>
                            <td class="px-6 py-4 text-slate-600">Near ST Bus Stand, Alibaug</td>
                            <td class="px-6 py-4 text-slate-500">Vegetables, fruits, local spices, clothing, household items</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Alibaug Fish Market</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-bold">Daily</span></td>
                            <td class="px-6 py-4 text-slate-600">6:00 AM — 11:00 AM</td>
                            <td class="px-6 py-4 text-slate-600">Alibaug Jetty Road</td>
                            <td class="px-6 py-4 text-slate-500">Fresh fish, prawns, crabs, bombil, pomfret, surmai</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Nagaon Fish Market</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-bold">Daily</span></td>
                            <td class="px-6 py-4 text-slate-600">6:30 AM — 10:00 AM</td>
                            <td class="px-6 py-4 text-slate-600">Nagaon Beach Road</td>
                            <td class="px-6 py-4 text-slate-500">Fresh catch, dried fish, kokam</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Rewas Weekly Market</td>
                            <td class="px-6 py-4"><span class="bg-primary/10 text-primary px-2 py-1 rounded-md text-xs font-bold">Sunday</span></td>
                            <td class="px-6 py-4 text-slate-600">8:00 AM — 2:00 PM</td>
                            <td class="px-6 py-4 text-slate-600">Rewas Village Centre</td>
                            <td class="px-6 py-4 text-slate-500">Farm produce, pottery, handlooms, snacks</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Kashid Village Market</td>
                            <td class="px-6 py-4"><span class="bg-primary/10 text-primary px-2 py-1 rounded-md text-xs font-bold">Wednesday</span></td>
                            <td class="px-6 py-4 text-slate-600">8:00 AM — 12:00 PM</td>
                            <td class="px-6 py-4 text-slate-600">Kashid Village Road</td>
                            <td class="px-6 py-4 text-slate-500">Organic vegetables, coconuts, local snacks</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Mandwa Jetty Market</td>
                            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-xs font-bold">Daily</span></td>
                            <td class="px-6 py-4 text-slate-600">9:00 AM — 6:00 PM</td>
                            <td class="px-6 py-4 text-slate-600">Near Mandwa Ferry Gate</td>
                            <td class="px-6 py-4 text-slate-500">Souvenirs, snacks, coconut water, local goods</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">Kihim Organic Farm Stands</td>
                            <td class="px-6 py-4"><span class="bg-emerald-50 text-emerald-600 px-2 py-1 rounded-md text-xs font-bold">Sat — Sun</span></td>
                            <td class="px-6 py-4 text-slate-600">8:00 AM — 12:00 PM</td>
                            <td class="px-6 py-4 text-slate-600">Kihim Beach Road</td>
                            <td class="px-6 py-4 text-slate-500">Organic greens, honey, cashews, jaggery, alphonso mangoes (seasonal)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tips --}}
        <div class="mt-10 bg-white rounded-2xl border border-slate-200 p-8">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">tips_and_updates</span> Market Tips
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5">check_circle</span>
                    <p>Arrive early for the freshest fish and produce — best selection is before 9 AM.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5">check_circle</span>
                    <p>Carry cash — most market vendors do not accept UPI or cards.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5">check_circle</span>
                    <p>Bargaining is expected at weekly bazaars but not at fish markets.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-primary text-[18px] mt-0.5">check_circle</span>
                    <p>Monsoon season (June–Sept) may affect timings — some markets close on heavy rain days.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

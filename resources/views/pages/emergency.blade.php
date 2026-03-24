@extends('layouts.app')
@section('title', 'Emergency Contacts in Alibaug — Hello Alibaug')
@section('meta_desc', 'Essential emergency contact numbers for Alibaug — Police, Hospital, Coast Guard, Fire Brigade, Ferry Emergency, and more. Save this page!')

@section('content')
<main class="bg-slate-50 min-h-screen">
    <div class="bg-white border-b border-slate-200 pt-16 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-red-50 text-red-600 px-4 py-2 rounded-full text-sm font-bold mb-6">
                <span class="material-symbols-outlined text-[18px]">emergency</span> Important — Save This Page
            </div>
            <h1 class="text-3xl md:text-5xl font-serif font-bold text-slate-900 mb-4">Emergency Contacts</h1>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">Essential phone numbers for emergencies in the Alibaug region. Bookmark this page for quick access.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Universal Emergency --}}
        <div class="bg-red-600 text-white rounded-2xl p-8 mb-8 text-center shadow-lg">
            <span class="material-symbols-outlined text-5xl mb-3">call</span>
            <h2 class="text-3xl font-bold mb-2">National Emergency</h2>
            <a href="tel:112" class="text-5xl font-bold tracking-wider hover:underline">112</a>
            <p class="text-red-200 mt-2">Works for Police, Fire, and Ambulance across India</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Police --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><span class="material-symbols-outlined">local_police</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Police</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Alibaug Police Station</span>
                        <a href="tel:02141222100" class="text-primary font-bold text-sm hover:underline">02141-222100</a>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Women Helpline</span>
                        <a href="tel:1091" class="text-primary font-bold text-sm hover:underline">1091</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Child Helpline</span>
                        <a href="tel:1098" class="text-primary font-bold text-sm hover:underline">1098</a>
                    </div>
                </div>
            </div>

            {{-- Medical --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><span class="material-symbols-outlined">local_hospital</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Medical</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Alibaug Civil Hospital</span>
                        <a href="tel:02141222326" class="text-primary font-bold text-sm hover:underline">02141-222326</a>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Ambulance</span>
                        <a href="tel:108" class="text-primary font-bold text-sm hover:underline">108</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Blood Bank Helpline</span>
                        <a href="tel:1910" class="text-primary font-bold text-sm hover:underline">1910</a>
                    </div>
                </div>
            </div>

            {{-- Fire & Rescue --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center"><span class="material-symbols-outlined">local_fire_department</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Fire & Rescue</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Fire Brigade</span>
                        <a href="tel:101" class="text-primary font-bold text-sm hover:underline">101</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Disaster Management</span>
                        <a href="tel:1070" class="text-primary font-bold text-sm hover:underline">1070</a>
                    </div>
                </div>
            </div>

            {{-- Maritime & Coast --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center"><span class="material-symbols-outlined">sailing</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Maritime & Coast Guard</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">Indian Coast Guard</span>
                        <a href="tel:1554" class="text-primary font-bold text-sm hover:underline">1554</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Maritime Rescue (MRCC Mumbai)</span>
                        <a href="tel:02222614065" class="text-primary font-bold text-sm hover:underline">022-2261 4065</a>
                    </div>
                </div>
            </div>

            {{-- Transport --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center"><span class="material-symbols-outlined">directions_bus</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Transport & Travel</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">MSRTC Bus Helpline</span>
                        <a href="tel:1800221250" class="text-primary font-bold text-sm hover:underline">1800-221-250</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">RIMT Ferry Booking (Mandwa)</span>
                        <a href="tel:02222823292" class="text-primary font-bold text-sm hover:underline">022-2282 3292</a>
                    </div>
                </div>
            </div>

            {{-- Utilities --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><span class="material-symbols-outlined">electrical_services</span></div>
                    <h3 class="text-lg font-bold text-slate-900">Utilities</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-100">
                        <span class="text-sm text-slate-600">MSEDCL (Power Complaints)</span>
                        <a href="tel:1912" class="text-primary font-bold text-sm hover:underline">1912</a>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-600">Gas Emergency</span>
                        <a href="tel:1906" class="text-primary font-bold text-sm hover:underline">1906</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Share CTA --}}
        <div class="mt-10 bg-slate-900 text-white rounded-2xl p-8 text-center">
            <h3 class="text-xl font-bold mb-2">Share This Page</h3>
            <p class="text-slate-400 text-sm mb-4">Send these numbers to friends, family, and fellow travellers.</p>
            <a href="https://wa.me/?text=Emergency+contacts+for+Alibaug:+{{ urlencode(url()->current()) }}" target="_blank"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-bold transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.832-1.438A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                Share on WhatsApp
            </a>
        </div>
    </div>
</main>
@endsection

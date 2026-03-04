@extends('layouts.app')

@section('title', 'Welcome — Get Started for Free')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-primary/5 flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-lg">

        {{-- Welcome Message --}}
        @if(session('success'))
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm font-medium px-4 py-3 rounded-xl mb-6 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 mb-5">
                <span class="material-symbols-outlined text-primary text-3xl">rocket_launch</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-slate-900 mb-3">
                Start Listing for Free
            </h1>
            <p class="text-slate-500 text-base leading-relaxed">
                Get your business discovered by thousands of visitors in Alibaug — absolutely free, no credit card needed.
            </p>
        </div>

        {{-- Free Plan Card --}}
        <div class="bg-white rounded-3xl border-2 border-primary shadow-xl shadow-primary/10 overflow-hidden">
            {{-- Top accent bar --}}
            <div class="h-1.5 bg-gradient-to-r from-primary to-blue-400"></div>

            <div class="p-8">
                {{-- Plan heading --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-primary mb-1">Free Plan</p>
                        <p class="text-4xl font-bold text-slate-900">₹0 <span class="text-base font-normal text-slate-400">/ forever</span></p>
                    </div>
                    <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1.5 rounded-full">
                        ✓ No payment needed
                    </span>
                </div>

                {{-- Features --}}
                <ul class="space-y-3 mb-8">
                    @foreach([
                        ['icon' => 'storefront',      'text' => '1 listing included'],
                        ['icon' => 'search',           'text' => 'Appear in search results & categories'],
                        ['icon' => 'mail',             'text' => 'Receive customer inquiries'],
                        ['icon' => 'star',             'text' => 'Collect reviews & ratings'],
                        ['icon' => 'dashboard',        'text' => 'Full owner dashboard access'],
                        ['icon' => 'event_available',  'text' => 'Availability calendar management'],
                        ['icon' => 'support_agent',    'text' => 'Customer support access'],
                    ] as $feat)
                    <li class="flex items-center gap-3 text-sm text-slate-700">
                        <span class="w-7 h-7 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-green-500 text-[15px]">check</span>
                        </span>
                        {{ $feat['text'] }}
                    </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                @if($subscription?->isActive())
                    <div class="flex items-center gap-3 justify-center w-full py-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 font-semibold">
                        <span class="material-symbols-outlined">check_circle</span>
                        You're already on the Free plan!
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ auth()->user()->isOwner() || auth()->user()->isAdmin() ? route('owner.dashboard') : route('home') }}"
                           class="text-sm text-primary font-medium hover:underline">
                            Go to {{ auth()->user()->isOwner() || auth()->user()->isAdmin() ? 'Dashboard' : 'Homepage' }} →
                        </a>
                    </div>
                @else
                    <form action="{{ route('subscription.free') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-primary hover:bg-primary/90 text-white font-bold text-base transition-all shadow-lg shadow-primary/25 flex items-center justify-center gap-2 group">
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">rocket_launch</span>
                            Get Started Free
                        </button>
                    </form>
                @endif
            </div>

            {{-- Upgrade teaser footer --}}
            <div class="bg-slate-50 border-t border-slate-100 px-8 py-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">workspace_premium</span>
                <p class="text-xs text-slate-400">
                    <strong class="text-slate-600">Premium plans coming soon</strong> — More listings, analytics & featured placement.
                </p>
            </div>
        </div>

        {{-- Trust badges --}}
        <div class="flex items-center justify-center gap-6 mt-8 text-xs text-slate-400">
            <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-green-500">lock</span>
                No credit card
            </span>
            <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-blue-500">upgrade</span>
                Cancel anytime
            </span>
            <span class="flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-primary">support_agent</span>
                Free support
            </span>
        </div>
    </div>
</div>
@endsection

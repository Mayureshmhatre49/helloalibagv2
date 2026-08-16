@extends('layouts.app')

@section('title', 'Pricing — List Your Business in Alibaug')
@section('meta_description', 'Simple, transparent pricing for business owners in Alibaug. List your stay, restaurant, service or event free during our founding member offer — no credit card required.')

@push('styles')
<style>
    .plans-hero {
        background:
            radial-gradient(90rem 40rem at 50% -20%, rgba(17,131,212,0.55) 0%, rgba(11,61,145,0) 60%),
            linear-gradient(180deg, #0b3d91 0%, #0a336f 100%);
    }
    .plans-hero-grid {
        background-image:
            linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 56px 56px;
        mask-image: radial-gradient(60rem 30rem at 50% 0%, #000 20%, transparent 75%);
        -webkit-mask-image: radial-gradient(60rem 30rem at 50% 0%, #000 20%, transparent 75%);
    }
    .plan-card-recommended {
        box-shadow: 0 24px 60px -20px rgba(11, 61, 145, 0.35), 0 0 0 1px rgba(17,131,212,0.18);
    }
    @media (prefers-reduced-motion: no-preference) {
        .plan-rise { animation: planRise .5s cubic-bezier(.22,.8,.32,1) both; }
        @keyframes planRise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    }
</style>
@endpush

@section('content')
@php
    $featureMatrix = [
        ['label' => 'Active listings',                'free' => '1',       'basic' => '3',              'premium' => 'Unlimited'],
        ['label' => 'Dedicated listing page',         'free' => true,      'basic' => true,             'premium' => true],
        ['label' => 'Appears in search & categories', 'free' => true,      'basic' => true,             'premium' => true],
        ['label' => 'Customer inquiries',             'free' => true,      'basic' => true,             'premium' => true],
        ['label' => 'Reviews & ratings',              'free' => true,      'basic' => true,             'premium' => true],
        ['label' => 'Owner dashboard',                'free' => true,      'basic' => true,             'premium' => true],
        ['label' => 'WhatsApp inquiry button',        'free' => false,     'basic' => true,             'premium' => true],
        ['label' => 'Featured badge',                 'free' => false,     'basic' => true,             'premium' => true],
        ['label' => 'Analytics & insights',           'free' => false,     'basic' => 'Basic',          'premium' => 'Full'],
        ['label' => 'Top placement in search',        'free' => false,     'basic' => false,            'premium' => true],
        ['label' => 'Custom profile branding',        'free' => false,     'basic' => false,            'premium' => true],
        ['label' => 'Social media promotion',         'free' => false,     'basic' => false,            'premium' => true],
        ['label' => 'Support',                        'free' => 'Email',   'basic' => 'Priority email', 'premium' => 'Account manager'],
    ];

    $faqs = [
        [
            'q' => 'Is it really free? What\'s the catch?',
            'a' => 'No catch. The ₹500 listing fee is fully waived for founding members, so you pay ₹0 today and no credit card is required. If our pricing changes later, we\'ll email you well in advance — your listing won\'t be switched off without notice.',
        ],
        [
            'q' => 'How long before my listing goes live?',
            'a' => 'After you submit your listing it goes to our team for review, which keeps the quality of the directory high. Once approved it appears in search, category pages and area pages across the site.',
        ],
        [
            'q' => 'What do I need to get started?',
            'a' => 'Just your business name, a short description, your area in Alibaug and a few photos. You don\'t need a website, GST number or any technical setup.',
        ],
        [
            'q' => 'I want to list a property under Real Estate.',
            'a' => 'Real Estate is a separate paid category handled offline — and it does not use up your free listing slot. Submit the listing any time and our team will contact you to arrange payment before it goes live.',
        ],
        [
            'q' => 'Can I edit my listing after it\'s published?',
            'a' => 'Yes. Your owner dashboard lets you update photos, description, pricing, contact details and availability whenever you need to.',
        ],
        [
            'q' => 'What happens to my listing when paid plans launch?',
            'a' => 'Nothing changes automatically. Your listing stays exactly as it is on the Free plan — Basic and Premium are optional upgrades for owners who want more listings, analytics and placement.',
        ],
    ];

    $planOrder = ['free', 'basic', 'premium'];
@endphp

{{-- ══════════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════════ --}}
<section class="plans-hero relative overflow-hidden">
    <div class="plans-hero-grid absolute inset-0 pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 sm:pt-20 pb-32 sm:pb-40 text-center">
        {{-- Offer eyebrow --}}
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white px-3.5 py-1.5 rounded-full mb-6">
            <span class="material-symbols-outlined text-[16px] text-amber-300" aria-hidden="true">verified</span>
            <span class="text-[11px] sm:text-xs font-bold uppercase tracking-[0.14em]">Founding Member Offer</span>
        </div>

        <h1 class="text-white font-serif font-bold text-3xl sm:text-5xl lg:text-[3.25rem] leading-[1.1] tracking-tight mb-5">
            Get your business found<br class="hidden sm:block"> by visitors to Alibaug
        </h1>

        <p class="text-blue-100/85 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto mb-9">
            One transparent plan to start. No credit card, no commission on your bookings,
            and no hidden fees — ever.
        </p>

        {{-- Real platform stats --}}
        <dl class="flex flex-wrap items-center justify-center gap-x-8 sm:gap-x-12 gap-y-4">
            @foreach ([
                ['value' => $stats['listings'],   'label' => 'Businesses listed'],
                ['value' => $stats['areas'],      'label' => 'Areas covered'],
                ['value' => $stats['categories'], 'label' => 'Categories'],
            ] as $stat)
                <div class="text-center">
                    <dd class="text-white font-bold text-2xl sm:text-3xl tabular-nums leading-none">{{ number_format($stat['value']) }}</dd>
                    <dt class="text-blue-200/70 text-[11px] sm:text-xs font-semibold uppercase tracking-wider mt-1.5">{{ $stat['label'] }}</dt>
                </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     PRICING CARDS (overlapping the hero)
══════════════════════════════════════════════════════════════ --}}
<section class="bg-slate-50 pb-16 sm:pb-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="relative -mt-24 sm:-mt-28 mb-6 max-w-xl mx-auto flex items-start gap-2.5 bg-white border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-xl shadow-lg">
                <span class="material-symbols-outlined text-[18px] text-green-600 shrink-0" aria-hidden="true">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div id="plans" class="relative grid lg:grid-cols-3 gap-5 lg:gap-6 items-stretch {{ session('success') ? '' : '-mt-24 sm:-mt-28' }}">

            @foreach($planOrder as $i => $key)
                @php
                    $planDef = $plans[$key];
                    $isFree = $key === 'free';
                    $isActiveOnFree = $subscription?->isActive() && $subscription->plan === 'free' && $isFree;
                    $alreadyInterested = in_array($key, $interestedPlans ?? []);
                @endphp

                <div class="plan-rise relative flex flex-col rounded-2xl bg-white transition-shadow duration-200
                            {{ $isFree ? 'plan-card-recommended lg:-mt-4' : 'border border-slate-200/90 shadow-sm hover:shadow-md' }}"
                     style="animation-delay: {{ $i * 70 }}ms">

                    {{-- Recommended ribbon --}}
                    @if($isFree)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 z-10">
                            <span class="inline-flex items-center gap-1.5 bg-[#e8831a] text-white text-[10px] font-bold uppercase tracking-[0.12em] px-3 py-1.5 rounded-full shadow-md whitespace-nowrap">
                                <span class="material-symbols-outlined text-[13px]" aria-hidden="true">star</span>
                                Recommended
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-col flex-1 p-6 sm:p-7 {{ $isFree ? 'pt-8 sm:pt-9' : '' }}">

                        {{-- Plan name + status --}}
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-sm font-bold uppercase tracking-[0.12em] {{ $isFree ? 'text-primary' : 'text-slate-500' }}">
                                {{ $planDef['name'] }}
                            </h2>
                            @if(!$planDef['available'])
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[12px]" aria-hidden="true">schedule</span>
                                    Soon
                                </span>
                            @endif
                        </div>

                        {{-- Price --}}
                        <div class="mb-1.5">
                            @if($isFree)
                                <div class="flex items-baseline gap-2.5 flex-wrap">
                                    <span class="text-slate-900 font-bold text-5xl tracking-tight tabular-nums">₹0</span>
                                    <span class="text-slate-400 text-lg font-medium line-through decoration-slate-300 tabular-nums">₹500</span>
                                </div>
                                <div class="inline-flex items-center gap-1.5 mt-3 bg-green-50 text-green-700 text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                                    <span class="material-symbols-outlined text-[13px]" aria-hidden="true">sell</span>
                                    ₹500 fee waived
                                </div>
                            @else
                                <div class="flex items-baseline gap-1.5 flex-wrap">
                                    <span class="text-slate-900 font-bold text-5xl tracking-tight tabular-nums">
                                        {{ $planDef['currency'] }}{{ number_format($planDef['price']) }}
                                    </span>
                                    <span class="text-slate-500 text-sm font-medium">/ month</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-slate-500 text-sm leading-relaxed mt-3 mb-6">
                            {{ $isFree
                                ? 'Everything you need to get discovered — free while we grow Alibaug\'s local guide.'
                                : $planDef['description'] }}
                        </p>

                        {{-- Divider --}}
                        <div class="h-px bg-slate-100 mb-6"></div>

                        {{-- Features --}}
                        <ul class="space-y-3.5 mb-8 flex-1">
                            @foreach($planDef['features'] as $feature)
                                <li class="flex items-start gap-3 text-[13.5px] text-slate-700 leading-snug">
                                    <span class="material-symbols-outlined text-[17px] {{ $isFree ? 'text-primary' : 'text-green-600' }} shrink-0 mt-px" aria-hidden="true">check_circle</span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- CTA --}}
                        <div class="mt-auto">
                            @if($isActiveOnFree)
                                <div class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl bg-green-50 border border-green-200 text-green-700 font-bold text-sm">
                                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">check_circle</span>
                                    Your current plan
                                </div>
                                @if($user && ($user->isOwner() || $user->isAdmin()))
                                    <a href="{{ route('owner.dashboard') }}"
                                       class="mt-3 flex items-center justify-center gap-1 text-sm text-primary font-semibold hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 rounded-lg py-1">
                                        Go to dashboard
                                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">arrow_forward</span>
                                    </a>
                                @endif

                            @elseif($isFree)
                                @php
                                    $freeHref = $user ? null : route('register', ['plan' => 'free', 'account_type' => 'owner']);
                                @endphp
                                @if($freeHref)
                                    <a href="{{ $freeHref }}"
                                       class="group w-full flex items-center justify-center gap-2 py-4 rounded-xl bg-[#e8831a] hover:bg-[#d06b10] text-white font-bold text-[15px] transition-all duration-200 shadow-lg shadow-[#e8831a]/25 hover:shadow-xl hover:shadow-[#e8831a]/30 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#e8831a]/30">
                                        List my business — free
                                        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true">arrow_forward</span>
                                    </a>
                                @else
                                    <form action="{{ route('subscription.free') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="group w-full flex items-center justify-center gap-2 py-4 rounded-xl bg-[#e8831a] hover:bg-[#d06b10] text-white font-bold text-[15px] transition-all duration-200 shadow-lg shadow-[#e8831a]/25 hover:shadow-xl hover:shadow-[#e8831a]/30 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#e8831a]/30">
                                            List my business — free
                                            <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true">arrow_forward</span>
                                        </button>
                                    </form>
                                @endif
                                <p class="text-center text-xs text-slate-400 mt-3">No credit card required</p>

                            @elseif($alreadyInterested)
                                <div class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl bg-green-50 border border-green-200 text-green-700 font-bold text-sm">
                                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">mark_email_read</span>
                                    You're on the waitlist
                                </div>

                            @elseif($user)
                                <form action="{{ route('subscription.interest', $key) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-800 font-bold text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-slate-200">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500" aria-hidden="true">notifications_active</span>
                                        Join the waitlist
                                    </button>
                                </form>

                            @else
                                <div x-data="{ open: false }">
                                    <button type="button" x-show="!open" @click="open = true; $nextTick(() => $refs.email{{ $key }}.focus())"
                                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-800 font-bold text-sm transition-all duration-200 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-slate-200">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500" aria-hidden="true">notifications_active</span>
                                        Join the waitlist
                                    </button>

                                    <form x-show="open" x-cloak style="display:none;"
                                          action="{{ route('subscription.interest', $key) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <label for="email-{{ $key }}" class="sr-only">Email address for {{ $planDef['name'] }} plan waitlist</label>
                                        <input id="email-{{ $key }}" x-ref="email{{ $key }}" type="email" name="email" required
                                               placeholder="you@example.com" autocomplete="email"
                                               class="w-full px-3.5 py-3 bg-white border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                                        <button type="submit"
                                            class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition-colors duration-200 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-slate-300">
                                            Notify me at launch
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Assurance strip --}}
        <div class="mt-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3">
            @foreach ([
                ['icon' => 'credit_card_off', 'text' => 'No credit card required'],
                ['icon' => 'percent',         'text' => 'Zero commission on bookings'],
                ['icon' => 'cancel',          'text' => 'Cancel or remove any time'],
                ['icon' => 'support_agent',   'text' => 'Free support from our team'],
            ] as $item)
                <span class="inline-flex items-center gap-2 text-slate-600 text-[13px] font-medium">
                    <span class="material-symbols-outlined text-[17px] text-primary" aria-hidden="true">{{ $item['icon'] }}</span>
                    {{ $item['text'] }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     COMPARISON TABLE
══════════════════════════════════════════════════════════════ --}}
<section class="bg-white border-y border-slate-200/80 py-16 sm:py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-serif font-bold text-2xl sm:text-3xl text-slate-900 mb-2">Compare every feature</h2>
            <p class="text-slate-500 text-sm sm:text-base">Everything in the Free plan stays free — upgrades are optional.</p>
        </div>

        <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
            <table class="w-full min-w-[560px] border-separate border-spacing-0 text-sm">
                <caption class="sr-only">Feature comparison across Free, Basic and Premium plans</caption>
                <thead>
                    <tr>
                        <th scope="col" class="text-left font-semibold text-slate-500 text-xs uppercase tracking-wider pb-4 pr-4 w-[40%]">Feature</th>
                        @foreach($planOrder as $key)
                            <th scope="col" class="pb-4 px-3 text-center">
                                <span class="block font-bold text-[13px] {{ $key === 'free' ? 'text-primary' : 'text-slate-700' }}">{{ $plans[$key]['name'] }}</span>
                                <span class="block text-[11px] font-medium text-slate-400 mt-0.5">
                                    {{ $key === 'free' ? '₹0' : $plans[$key]['currency'] . number_format($plans[$key]['price']) . '/mo' }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($featureMatrix as $row)
                        <tr class="group">
                            <th scope="row" class="text-left font-medium text-slate-700 py-3.5 pr-4 border-t border-slate-100 align-middle">
                                {{ $row['label'] }}
                            </th>
                            @foreach($planOrder as $key)
                                <td class="py-3.5 px-3 text-center border-t border-slate-100 align-middle {{ $key === 'free' ? 'bg-primary/[0.03]' : '' }}">
                                    @if($row[$key] === true)
                                        <span class="material-symbols-outlined text-[19px] text-green-600 align-middle" aria-label="Included">check</span>
                                    @elseif($row[$key] === false)
                                        <span class="material-symbols-outlined text-[19px] text-slate-300 align-middle" aria-label="Not included">remove</span>
                                    @else
                                        <span class="font-semibold text-slate-800 text-[13px]">{{ $row[$key] }}</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════════════ --}}
<section class="bg-slate-50 py-16 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="font-serif font-bold text-2xl sm:text-3xl text-slate-900 mb-2">Questions, answered</h2>
            <p class="text-slate-500 text-sm sm:text-base">Everything owners usually ask before listing.</p>
        </div>

        <div class="space-y-3" x-data="{ open: 0 }">
            @foreach($faqs as $i => $faq)
                <div class="bg-white rounded-xl border border-slate-200/90 overflow-hidden">
                    <h3>
                        <button type="button"
                                @click="open = (open === {{ $i }} ? null : {{ $i }})"
                                :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                                aria-controls="faq-panel-{{ $i }}"
                                class="w-full flex items-center justify-between gap-4 text-left px-5 py-4 hover:bg-slate-50/70 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary/40">
                            <span class="font-semibold text-slate-900 text-[15px] leading-snug">{{ $faq['q'] }}</span>
                            <span class="material-symbols-outlined text-[20px] text-slate-400 shrink-0 transition-transform duration-200"
                                  :class="open === {{ $i }} ? 'rotate-180' : ''" aria-hidden="true">expand_more</span>
                        </button>
                    </h3>
                    <div id="faq-panel-{{ $i }}" x-show="open === {{ $i }}" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div class="px-5 pb-5 pt-0 text-slate-600 text-sm leading-relaxed">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Real Estate note --}}
        <div class="mt-8 bg-amber-50 border border-amber-200/80 rounded-xl px-5 py-4 flex gap-3">
            <span class="material-symbols-outlined text-amber-600 text-[20px] shrink-0" aria-hidden="true">real_estate_agent</span>
            <p class="text-[13px] text-amber-900 leading-relaxed">
                <strong class="font-bold">Listing under Real Estate?</strong>
                It's a separate paid category with offline payment — and it doesn't use up your free listing slot.
                Submit any time and our team will contact you to arrange payment before it goes live.
            </p>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     FINAL CTA
══════════════════════════════════════════════════════════════ --}}
@if(!($subscription?->isActive() && $user && ($user->isOwner() || $user->isAdmin())))
<section class="bg-[#0b3d91] py-16 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-white font-serif font-bold text-2xl sm:text-4xl mb-4 leading-tight">
            Ready to get discovered?
        </h2>
        <p class="text-blue-100/80 text-sm sm:text-base mb-8 max-w-xl mx-auto leading-relaxed">
            Join the businesses already reaching visitors across {{ number_format($stats['areas']) }} areas of Alibaug.
            Setting up takes a few minutes.
        </p>
        <a href="{{ $user ? route('subscription.plans') . '#plans' : route('register', ['plan' => 'free', 'account_type' => 'owner']) }}"
           class="group inline-flex items-center justify-center gap-2 bg-[#e8831a] hover:bg-[#d06b10] text-white font-bold text-[15px] px-8 py-4 rounded-xl transition-all duration-200 shadow-xl shadow-black/20 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#e8831a]/40">
            List my business — free
            <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-0.5" aria-hidden="true">arrow_forward</span>
        </a>
        <p class="text-blue-200/60 text-xs mt-4">₹500 fee waived · No credit card required</p>
    </div>
</section>
@endif
@endsection

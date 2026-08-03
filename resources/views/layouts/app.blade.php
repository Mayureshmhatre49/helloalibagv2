<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- ── SEO ─────────────────────────────────────────────────────────────
         Per-page overrides via @section: title, meta_description, og_image,
         og_type, canonical, robots, and jsonld (structured data). --}}
    <title>{{ config('app.name', 'Hello Alibaug') }} — @yield('title', 'Discover Alibaug')</title>
    <meta name="description" content="@yield('meta_description', 'Hello Alibaug — Your gateway to luxury villas, dining, events, and experiences in Alibaug.')">
    @hasSection('keywords')<meta name="keywords" content="@yield('keywords')">@endif
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ config('app.name') }} — @yield('title', 'Discover Alibaug')">
    <meta property="og:description" content="@yield('meta_description', 'Discover luxury stays, premium real estate, and authentic local experiences in Alibaug.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="en_IN">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name') }} — @yield('title', 'Discover Alibaug')">
    <meta name="twitter:description" content="@yield('meta_description', 'Discover luxury stays, premium real estate, and authentic local experiences in Alibaug.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">

    @yield('jsonld')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background-light text-slate-900 font-display antialiased overflow-x-hidden">
    {{-- Navbar --}}
    <header class="sticky top-0 z-[9999] w-full transition-all duration-300" 
            x-data="{ 
                mobileOpen: false, 
                mobileSearch: false,
                isHome: {{ request()->routeIs('home') ? 'true' : 'false' }},
                isMap: {{ request()->routeIs('map.*') ? 'true' : 'false' }},
                scrolled: false 
            }" 
            @scroll.window="if (!isMap) scrolled = (window.pageYOffset > 300)"
            :class="scrolled ? 'shadow-xl' : 'shadow-lg'">

        {{-- ── Primary Blue Header ─────────────────────────────────────────── --}}
        <div class="bg-[#0b3d91] relative z-50 transition-all duration-300">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="flex items-center h-16 gap-4">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 mr-2 group">
                        <img src="{{ asset('images/helloalibaug-logo.png') }}" alt="Hello Alibaug — Discover, Stay, Eat" class="h-11 sm:h-14 w-auto">
                    </a>

                    {{-- Desktop Categories — flex child between logo and actions. Core
                         browse items stay inline; secondary links live in "More". --}}
                    {{-- $navCategories is supplied (cached) by the layouts.app view composer. --}}
                    @php $moreActive = request()->routeIs('guides.*') || request()->routeIs('blog.*') || request()->routeIs('marketplace.*'); @endphp
                    @php
                        // request()->route('category') can be an unbound raw string when the
                        // {category:slug} constraint doesn't match (e.g. a slug that isn't a
                        // real category) — guard with instanceof rather than assuming a model.
                        $routeCategory = request()->route('category');
                        $currentCategoryId = $routeCategory instanceof \App\Models\Category ? $routeCategory->id : null;
                    @endphp
                    <div class="hidden lg:flex flex-1 min-w-0 items-center justify-center gap-1 xl:gap-1.5">
                        <a href="{{ route('search') }}"
                            class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ !request()->routeIs('category.show') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            All
                        </a>
                        @foreach($navCategories as $cat)
                        <a href="{{ route('category.show', $cat) }}"
                            class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ $currentCategoryId === $cat->id ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            {{ $cat->name }}
                        </a>
                        @endforeach
                        <a href="{{ route('map.index') }}"
                            class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ request()->routeIs('map.*') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            <span class="material-symbols-outlined text-[16px]">map</span>
                            Map
                        </a>

                        {{-- More dropdown (secondary links) --}}
                        <div x-data="{ moreOpen: false }" class="relative">
                            <button @click="moreOpen = !moreOpen" @click.outside="moreOpen = false" type="button"
                                class="flex items-center gap-1 px-3.5 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ $moreActive ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                                More
                                <span class="material-symbols-outlined text-[18px] transition-transform" :class="moreOpen ? 'rotate-180' : ''">expand_more</span>
                            </button>
                            <div x-show="moreOpen" x-transition style="display:none;"
                                 class="absolute left-1/2 -translate-x-1/2 mt-3 w-56 bg-white rounded-xl shadow-2xl border border-slate-100 py-2 z-50 origin-top">
                                <a href="{{ route('guides.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('guides.*') ? 'text-primary bg-primary/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">menu_book</span> Guides
                                </a>
                                <a href="{{ route('blog.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('blog.*') ? 'text-primary bg-primary/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">article</span> Blog
                                </a>
                                <a href="{{ route('marketplace.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('marketplace.*') ? 'text-primary bg-primary/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">storefront</span> Buy &amp; Sell
                                </a>
                                <div class="my-1.5 border-t border-slate-100"></div>
                                <a href="{{ route('page.beaches') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('page.beaches') ? 'text-primary bg-primary/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">beach_access</span> Beaches
                                </a>
                                <a href="{{ route('page.ferry-schedule') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium {{ request()->routeIs('page.ferry-schedule') ? 'text-primary bg-primary/5' : 'text-slate-700 hover:bg-slate-50' }} transition-colors">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">directions_boat</span> Ferry Timings
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-2 sm:gap-3 ml-auto lg:ml-0 flex-shrink-0">
                        
                        {{-- Search Button (Hidden on Home until Scrolled) --}}
                        <button x-show="!isHome || scrolled" 
                                x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 scale-90 translate-x-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                                x-transition:leave-end="opacity-0 scale-90 translate-x-4"
                                style="display: none;"
                                @click="mobileSearch = !mobileSearch" 
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 hover:bg-white/20 shadow-sm flex items-center justify-center text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px] sm:text-[22px]" x-text="mobileSearch ? 'close' : 'search'">search</span>
                        </button>

                        @auth
                            {{-- Wishlist --}}
                            <a href="{{ route('wishlist.index') }}" class="hidden sm:flex w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 hover:bg-white/20 items-center justify-center text-white transition-colors shadow-sm" title="Wishlist">
                                <span class="material-symbols-outlined text-[20px] sm:text-[22px]">favorite</span>
                            </a>

                            {{-- Notifications --}}
                            <div x-data="{ notifOpen: false }" class="relative">
                                <button @click="notifOpen = !notifOpen" class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[20px] sm:text-[22px]">notifications</span>
                                    @php $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->unread()->count(); @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                    @endif
                                </button>
                                <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-slate-100 z-50 overflow-hidden origin-top-right">
                                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                        <span class="text-sm font-bold text-slate-900">Notifications</span>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-primary font-bold hover:underline">View All</a>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                                        @php $latestNotifs = \App\Models\UserNotification::where('user_id', auth()->id())->latest()->take(5)->get(); @endphp
                                        @forelse($latestNotifs as $notif)
                                            <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-3 hover:bg-slate-50 transition-colors flex items-start gap-3 {{ $notif->read_at ? '' : 'bg-primary/5' }}">
                                                    <span class="material-symbols-outlined {{ $notif->getIconClass() }} text-[18px] mt-0.5">{{ $notif->getIcon() }}</span>
                                                    <div>
                                                        <p class="text-xs font-semibold text-slate-900 line-clamp-1">{{ $notif->title }}</p>
                                                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $notif->message }}</p>
                                                        <p class="text-[10px] text-slate-500 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </button>
                                            </form>
                                        @empty
                                            <div class="px-4 py-8 text-center text-xs text-slate-500 flex flex-col items-center gap-2">
                                                <span class="material-symbols-outlined text-3xl text-slate-200">notifications_off</span>
                                                All caught up!
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- User Avatar Dropdown --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2">
                                    <img src="{{ auth()->user()->getAvatarUrl() }}"
                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl object-cover border-2 border-white/30 hover:border-white/60 transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-white/70 text-[18px] hidden sm:block">expand_more</span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-2xl border border-slate-100 py-2 z-50 origin-top-right">
                                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 mb-1">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                                        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[18px] opacity-70">dashboard</span> Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('subscription.plans') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-bold text-primary hover:bg-primary/5 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">add_business</span> List Your Business
                                        </a>
                                    @endif
                                    <a href="{{ route('trips.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">luggage</span> My Trips
                                    </a>
                                    <a href="{{ route('marketplace.my') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">sell</span> My Items
                                    </a>
                                    <a href="{{ route('bookings.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">book_online</span> My Bookings
                                    </a>
                                    <a href="{{ route('subscription.plans') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">workspace_premium</span>
                                        My Plan
                                        <span class="ml-auto text-[10px] bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">{{ auth()->user()->subscription?->plan ?? 'Free' }}</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">person</span> Profile
                                    </a>
                                    <div class="border-t border-slate-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                                <span class="material-symbols-outlined text-[18px] opacity-70">logout</span> Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- White background Login Button with Light Shadow --}}
                            <a href="{{ route('login') }}" class="hidden sm:flex bg-white/95 hover:bg-white text-[#0b3d91] shadow-[0_2px_10px_rgba(0,0,0,0.1)] px-5 py-2 sm:py-2.5 rounded-xl text-sm font-bold transition-all">Log In</a>
                            <a href="{{ route('register') }}" class="bg-[#e8831a] hover:bg-[#d06b10] text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-sm font-bold transition-all shadow-[0_4px_14px_rgba(232,131,26,0.3)] flex items-center gap-1.5 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:ring-offset-[#0b3d91]">
                                <span class="material-symbols-outlined text-[16px]">add_business</span>
                                <span class="hidden sm:inline">List for Free</span>
                                <span class="sm:hidden">Join</span>
                            </a>
                        @endauth

                        {{-- Mobile Hamburger --}}
                        <button @click="mobileOpen = !mobileOpen" class="xl:hidden p-2 text-white/80 hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[24px]" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
                        </button>
                    </div>
                </div>

                {{-- Mobile/Tablet scrollable categories (Below top bar) --}}
                <div class="xl:hidden flex items-center gap-2 overflow-x-auto scrollbar-none pb-3 pt-1 border-t border-white/10">
                    <a href="{{ route('search') }}"
                            class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ !request()->routeIs('category.show') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            All
                    </a>
                    @foreach($navCategories as $cat)
                    <a href="{{ route('category.show', $cat) }}"
                        class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ $currentCategoryId === $cat->id ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                    <a href="{{ route('map.index') }}"
                        class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ request()->routeIs('map.*') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined text-[14px]">map</span>
                        Map
                    </a>
                    <a href="{{ route('guides.index') }}"
                        class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ request()->routeIs('guides.*') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined text-[14px]">menu_book</span>
                        Guides
                    </a>
                    <a href="{{ route('blog.index') }}"
                        class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ request()->routeIs('blog.*') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        Blog
                    </a>
                    <a href="{{ route('marketplace.index') }}"
                        class="flex shrink-0 items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all {{ request()->routeIs('marketplace.*') ? 'bg-white/20 text-white backdrop-blur-md shadow-inner' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                        <span class="material-symbols-outlined text-[14px]">storefront</span>
                        Buy &amp; Sell
                    </a>
                </div>
            </div>

            {{-- Full Width Search Dropdown (Toggled by the UI Search Button) --}}
            <div x-show="mobileSearch" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 style="display: none;"
                 class="absolute top-full left-0 w-full bg-[#083180] border-t border-white/10 py-4 px-4 sm:px-6 lg:px-8 z-40 shadow-2xl backdrop-blur-md">
                <form action="{{ route('search') }}" method="GET" class="max-w-[1280px] mx-auto flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1 bg-white rounded-xl shadow-inner overflow-hidden flex items-center h-12 sm:h-14">
                        <span class="material-symbols-outlined absolute left-4 text-primary/60 text-[24px]">search</span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search villas, restaurants, experiences..."
                            class="w-full h-full pl-12 pr-4 text-sm sm:text-base border-0 focus:ring-0 outline-none placeholder:text-slate-500 text-slate-900 font-medium">
                    </div>
                    <button type="submit" class="bg-[#e8831a] hover:bg-[#d06b10] transition-colors shadow-lg shadow-orange-900/30 text-white px-8 h-12 sm:h-14 rounded-xl text-base font-bold flex shrink-0 items-center justify-center gap-2">
                        <span>Search</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ── Mobile Full Nav (Hamburger menu contents) ───────────────────── --}}
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             style="display: none;"
             class="xl:hidden bg-white border-b border-slate-200 shadow-xl absolute top-full left-0 w-full">
            <div class="px-4 pt-3 pb-5 space-y-1 max-h-[70vh] overflow-y-auto">
                <a href="{{ route('blog.index') }}"
                    class="flex items-center gap-3 px-3 py-3 text-sm font-semibold text-slate-700 hover:text-primary hover:bg-primary/5 rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[20px] {{ request()->routeIs('blog.*') ? 'text-primary' : 'text-slate-400' }}">article</span>
                    Blog
                </a>
                
                <div class="border-t border-slate-100 pt-4 mt-3 space-y-2">
                    @guest
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-[20px] text-slate-400">login</span>
                            Log In
                        </a>
                    @endguest
                    @auth
                        <a href="{{ auth()->user()->isOwner() || auth()->user()->isAdmin() ? route('owner.onboarding.start') : route('subscription.plans') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-bold text-primary bg-primary/5 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-[20px]">add_business</span>
                            List Your Business
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-bold text-primary bg-primary/5 rounded-xl transition-colors">
                            <span class="material-symbols-outlined text-[20px]">add_business</span>
                            List Your Business
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-24 right-4 z-50 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button @click="show = false" class="ml-2 text-green-400 hover:text-green-600"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-4 z-50 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-red-600">error</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button @click="show = false" class="ml-2 text-red-400 hover:text-red-600"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-charcoal text-white pt-16 pb-8">
        <div class="max-w-[1280px] mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                {{-- Brand --}}
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center mb-4 group">
                        <img src="{{ asset('images/helloalibaug-logo.png') }}" alt="Hello Alibaug — Discover, Stay, Eat" class="h-14 w-auto">
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed">Connecting you with the finest stays, dining, events, and real estate in Alibaug.</p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase tracking-wider text-slate-300">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('page.about') }}" class="hover:text-primary transition-colors">About Us</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-primary transition-colors">Contact</a></li>
                        <li><a href="{{ route('search') }}" class="hover:text-primary transition-colors">Browse Listings</a></li>
                        <li><a href="{{ route('map.index') }}" class="hover:text-primary transition-colors">Explore on Map</a></li>
                        <li><a href="{{ route('events.calendar') }}" class="hover:text-primary transition-colors">Events Calendar</a></li>
                        <li><a href="{{ route('guides.index') }}" class="hover:text-primary transition-colors">Travel Guides</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-primary transition-colors">Blog</a></li>
                        <li><a href="{{ route('weather.index') }}" class="hover:text-primary transition-colors">Weather & Best Time</a></li>
                        <li><a href="{{ route('page.how-to-reach') }}" class="hover:text-primary transition-colors">How to Reach</a></li>
                        <li><a href="{{ route('page.ferry-schedule') }}" class="hover:text-primary transition-colors">Ferry Timings</a></li>
                        <li><a href="{{ route('page.beaches') }}" class="hover:text-primary transition-colors">Beaches Guide</a></li>
                        <li><a href="{{ auth()->guest() ? route('register') : ((auth()->user()->isOwner() || auth()->user()->isAdmin()) ? route('owner.dashboard') : route('subscription.plans')) }}" class="hover:text-primary transition-colors">List Your Business</a></li>
                    </ul>
                </div>

                {{-- Categories / Explore --}}
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase tracking-wider text-slate-300">Explore</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        @if(isset($navCategories) && $navCategories->count() > 0)
                            @foreach($navCategories as $cat)
                                <li><a href="{{ route('category.show', $cat) }}" class="hover:text-primary transition-colors">{{ $cat->name }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('search', ['type' => 'stay']) }}" class="hover:text-primary transition-colors">Stays & Private Villas</a></li>
                            <li><a href="{{ route('search', ['type' => 'eat']) }}" class="hover:text-primary transition-colors">Restaurants & Cafes</a></li>
                            <li><a href="{{ route('search', ['type' => 'real-estate']) }}" class="hover:text-primary transition-colors">Real Estate & Plots</a></li>
                            <li><a href="{{ route('search', ['type' => 'services']) }}" class="hover:text-primary transition-colors">Local Services & Concierge</a></li>
                            <li><a href="{{ route('page.beaches') }}" class="hover:text-primary transition-colors">Beaches & Coastal Spots</a></li>
                        @endif
                    </ul>
                </div>

                {{-- Legal --}}
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase tracking-wider text-slate-300">Legal</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-primary transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('page.terms') }}" class="hover:text-primary transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-700 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-slate-400">© {{ date('Y') }} Hello Alibaug. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="https://www.instagram.com/helloalibaug/" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px]"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    <a href="mailto:hello@helloalibaug.com" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><span class="material-symbols-outlined text-[18px]">alternate_email</span></a>
                    <a href="https://helloalibaug.com" target="_blank" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><span class="material-symbols-outlined text-[18px]">public</span></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

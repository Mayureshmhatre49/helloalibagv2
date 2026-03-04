<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @hasSection('seo')
        @yield('seo')
    @else
        <title>{{ config('app.name', 'Hello Alibaug') }} — @yield('title', 'Discover Alibaug')</title>
        <meta name="description" content="@yield('meta_description', 'Hello Alibaug — Your gateway to luxury villas, dining, events, and experiences in Alibaug.')">
        <meta property="og:title" content="{{ config('app.name') }} — @yield('title', 'Discover Alibaug')">
        <meta property="og:description" content="@yield('meta_description', 'Discover luxury stays, premium real estate, and authentic local experiences in Alibaug.')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->url() }}">
        <link rel="canonical" href="{{ request()->url() }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Noto+Serif+Display:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background-light text-slate-900 font-display antialiased overflow-x-hidden">
    {{-- Navbar --}}
    <header class="sticky top-0 z-50 w-full" x-data="{ mobileOpen: false, mobileSearch: false }">

        {{-- ── Tier 1: Brand + Search + Auth ────────────────────────────────── --}}
        <div class="bg-[#0b3d91] shadow-lg relative">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-16 gap-4">

                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 mr-2 group">
                        {{-- Icon badge --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg"
                             style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%); box-shadow: 0 4px 14px rgba(232,160,32,0.4);">
                            <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">sailing</span>
                        </div>
                        {{-- Brand text --}}
                        <div class="hidden sm:flex flex-col leading-none">
                            <span class="text-white font-extrabold text-[17px] tracking-tight leading-none">Hello <span style="color: #f5c842;">Alibaug</span></span>
                            <span class="text-white/50 text-[10px] tracking-widest uppercase mt-2 font-medium">Discover · Stay · Eat</span>
                        </div>
                    </a>

                    {{-- Desktop Search Bar --}}
                    <form action="{{ route('search') }}" method="GET" class="flex-1 hidden md:flex max-w-2xl">
                        <div class="flex w-full bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow h-11">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Search villas, restaurants, experiences..."
                                    class="w-full h-full pl-10 pr-4 text-sm text-slate-700 border-0 focus:ring-0 focus:outline-none bg-transparent placeholder:text-slate-400">
                            </div>
                            <button type="submit" class="bg-[#e8831a] hover:bg-[#d06b10] text-white px-5 text-sm font-bold transition-colors flex items-center gap-1.5 flex-shrink-0">
                                <span class="material-symbols-outlined text-[16px]">search</span>
                                Search
                            </button>
                        </div>
                    </form>

                    {{-- Mobile Search Toggle --}}
                    <button @click="mobileSearch = !mobileSearch" class="md:hidden p-2 text-white/80 hover:text-white ml-auto">
                        <span class="material-symbols-outlined text-[22px]" x-text="mobileSearch ? 'close' : 'search'">search</span>
                    </button>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-2 sm:gap-3 ml-auto md:ml-0">
                        @auth
                            {{-- Wishlist --}}
                            <a href="{{ route('wishlist.index') }}" class="hidden sm:flex w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 items-center justify-center text-white transition-colors" title="Wishlist">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </a>

                            {{-- Notifications --}}
                            <div x-data="{ notifOpen: false }" class="relative">
                                <button @click="notifOpen = !notifOpen" class="relative w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">notifications</span>
                                    @php $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->unread()->count(); @endphp
                                    @if($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                    @endif
                                </button>
                                <div x-show="notifOpen" @click.outside="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                                    <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                                        <span class="text-sm font-bold text-slate-900">Notifications</span>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-primary font-medium hover:underline">View All</a>
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
                                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                                    </div>
                                                </button>
                                            </form>
                                        @empty
                                            <div class="px-4 py-8 text-center text-xs text-slate-400">No notifications yet</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            {{-- User Avatar Dropdown --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2">
                                    <img src="{{ auth()->user()->getAvatarUrl() }}"
                                        class="w-9 h-9 rounded-xl object-cover border-2 border-white/30 hover:border-white/60 transition-colors">
                                    <span class="material-symbols-outlined text-white/70 text-[16px] hidden sm:block">expand_more</span>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                                    <div class="px-4 py-3 border-b border-slate-100">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                                        <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                            <span class="material-symbols-outlined text-[18px] text-primary">dashboard</span> Dashboard
                                        </a>
                                    @endif
                                    <a href="{{ route('bookings.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500">book_online</span> My Bookings
                                    </a>
                                    <a href="{{ route('subscription.plans') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500">workspace_premium</span>
                                        My Plan
                                        <span class="ml-auto text-[10px] bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-full uppercase">{{ auth()->user()->subscription?->plan ?? 'Free' }}</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-[18px] text-slate-500">person</span> Profile
                                    </a>
                                    <div class="border-t border-slate-100 mt-1.5">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50">
                                                <span class="material-symbols-outlined text-[18px]">logout</span> Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:flex text-sm font-medium text-white/80 hover:text-white transition-colors px-3 py-2">Log In</a>
                            <a href="{{ route('register') }}" class="bg-[#e8831a] hover:bg-[#d06b10] text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-md shadow-orange-900/20 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">add_business</span>
                                <span class="hidden sm:inline">List for Free</span>
                                <span class="sm:hidden">Join</span>
                            </a>
                        @endauth

                        {{-- Mobile Hamburger --}}
                        <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-white/80 hover:text-white">
                            <span class="material-symbols-outlined text-[22px]" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Search Dropdown --}}
            <div x-show="mobileSearch" x-transition class="md:hidden bg-[#083180] px-4 pb-3">
                <form action="{{ route('search') }}" method="GET">
                    <div class="flex bg-white rounded-xl overflow-hidden h-11">
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..."
                                class="w-full h-full pl-9 pr-3 text-sm border-0 focus:ring-0 outline-none">
                        </div>
                        <button type="submit" class="bg-[#e8831a] text-white px-4 text-sm font-bold">Search</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Tier 2: Categories Navigation Bar ────────────────────────────── --}}
        <div class="bg-white border-b border-slate-200 shadow-sm">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                @php $navCategories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
                <div class="flex items-center gap-1 overflow-x-auto scrollbar-none h-11">
                    <a href="{{ route('search') }}"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ !request()->routeIs('category.show') ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1">explore</span>
                        All
                    </a>
                    @foreach($navCategories as $cat)
                    <a href="{{ route('category.show', $cat) }}"
                        class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold whitespace-nowrap transition-all {{ request()->routeIs('category.show') && request()->route('category')?->id === $cat->id ? 'bg-primary text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $cat->icon ?? 'storefront' }}</span>
                        {{ $cat->name }}
                    </a>
                    @endforeach
                    <div class="ml-auto pl-4 flex-shrink-0 hidden md:block">
                        <a href="{{ route('owner.onboarding.start') }}"
                            class="flex items-center gap-1.5 text-primary hover:text-primary/80 text-sm font-bold whitespace-nowrap transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            Add Listing
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Mobile Full Nav ───────────────────────────────────────────────── --}}
        <div x-show="mobileOpen" x-transition class="md:hidden bg-white border-b border-slate-200 shadow-lg">
            <div class="px-4 pt-3 pb-4 space-y-1">
                @foreach($navCategories as $cat)
                    <a href="{{ route('category.show', $cat) }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-primary hover:bg-primary/5 rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-primary">{{ $cat->icon ?? 'storefront' }}</span>
                        {{ $cat->name }}
                    </a>
                @endforeach
                <div class="border-t border-slate-100 pt-3 mt-2">
                    @guest
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 rounded-xl">Log In</a>
                    @endguest
                    <a href="{{ route('owner.onboarding.start') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-primary rounded-xl">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span> Add Your Listing
                    </a>
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
                    <a href="{{ route('home') }}" class="flex items-center gap-3 mb-4 group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg"
                             style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%); box-shadow: 0 4px 14px rgba(232,160,32,0.35);">
                            <span class="material-symbols-outlined text-white text-[22px]" style="font-variation-settings:'FILL' 1">sailing</span>
                        </div>
                        <div class="leading-none">
                            <p class="text-white font-extrabold text-[17px] tracking-tight leading-none">Hello <span style="color: #f5c842;">Alibaug</span></p>
                            <p class="text-white/40 text-[10px] tracking-widest uppercase mt-2 font-medium">Discover · Stay · Eat</p>
                        </div>
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
                        <li><a href="{{ route('register') }}" class="hover:text-primary transition-colors">List Your Business</a></li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="font-bold text-sm mb-4 uppercase tracking-wider text-slate-300">Explore</h4>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        @php $footerCats = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
                        @foreach($footerCats as $cat)
                            <li><a href="{{ route('category.show', $cat) }}" class="hover:text-primary transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
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
                <p class="text-sm text-slate-500">© {{ date('Y') }} Hello Alibaug. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><span class="material-symbols-outlined text-[18px]">photo_camera</span></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><span class="material-symbols-outlined text-[18px]">alternate_email</span></a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white transition-all"><span class="material-symbols-outlined text-[18px]">public</span></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

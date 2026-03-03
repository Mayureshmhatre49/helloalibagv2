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
    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-slate-200" x-data="{ mobileOpen: false }">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-xl">waves</span>
                    </div>
                    <span class="text-xl font-serif font-bold tracking-tight text-slate-900">Hello Alibaug</span>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-8">
                    @php $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->get(); @endphp
                    @foreach($categories->take(4) as $cat)
                        <a href="{{ route('category.show', $cat) }}" class="text-sm font-medium text-slate-600 hover:text-primary transition-colors">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </nav>

                {{-- Right Actions --}}
                <div class="flex items-center gap-4">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex text-sm font-medium text-slate-600 hover:text-primary">Admin</a>
                        @endif
                        <a href="{{ route('wishlist.index') }}" class="hidden sm:flex text-slate-500 hover:text-red-500 transition-colors" title="Wishlist">
                            <span class="material-symbols-outlined text-[22px]">favorite</span>
                        </a>
                        {{-- Notifications Bell --}}
                        <div x-data="{ notifOpen: false }" class="relative">
                            <button @click="notifOpen = !notifOpen" class="relative text-slate-500 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[22px]">notifications</span>
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
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-primary">
                                <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border-2 border-slate-200">
                            </button>
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                                    <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                        <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                                    </a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                    <span class="material-symbols-outlined text-[18px]">person</span> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600">
                                        <span class="material-symbols-outlined text-[18px]">logout</span> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:flex text-sm font-medium text-slate-600 hover:text-primary">Log In</a>
                        <a href="{{ route('register') }}" class="bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded-full text-sm font-bold transition-all shadow-lg shadow-primary/20">
                            List Your Business
                        </a>
                    @endauth

                    {{-- Mobile Menu --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-slate-600">
                        <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <div x-show="mobileOpen" x-transition class="md:hidden border-t border-slate-200 bg-white">
            <div class="px-4 py-4 space-y-1">
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat) }}" class="block px-3 py-2.5 text-sm font-medium text-slate-600 hover:text-primary hover:bg-slate-50 rounded-lg">{{ $cat->name }}</a>
                @endforeach
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
                    <div class="flex items-center gap-2 mb-4">
                        <div class="size-8 bg-primary rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-xl">waves</span>
                        </div>
                        <span class="text-xl font-serif font-bold">Hello Alibaug</span>
                    </div>
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

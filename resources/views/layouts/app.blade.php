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
    <footer class="bg-slate-50 py-12 border-t border-slate-200">
        <div class="max-w-[1280px] mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="size-6 bg-primary rounded flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-sm">waves</span>
                    </div>
                    <span class="text-lg font-serif font-bold text-slate-900">Hello Alibaug</span>
                </div>
                <div class="text-sm text-slate-500">
                    © {{ date('Y') }} Hello Alibaug. All rights reserved.
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">photo_camera</span></a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">alternate_email</span></a>
                    <a href="#" class="text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">public</span></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

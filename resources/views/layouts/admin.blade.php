<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-[#f1f1f3] text-text-main font-display antialiased" x-data="{ sidebarOpen: false }">
    @php
        // Polaris-style nav-item classes (light theme, blue accent).
        $navBase   = 'group relative flex items-center gap-3 px-3 py-2 rounded-lg text-[13px] font-medium transition-colors';
        $navIdle   = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
        $navActive = 'bg-primary/10 text-primary font-semibold';
        $navIcon   = 'material-symbols-outlined text-[19px]';
        $badge     = 'ml-auto min-w-[20px] text-center text-[10px] leading-none px-1.5 py-1 rounded-full font-bold';
    @endphp

    {{-- ── Top bar (full width) ─────────────────────────────── --}}
    <header class="sticky top-0 z-40 bg-white border-b border-border-light h-14 flex items-center gap-3 px-4 sm:px-5">
        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-text-secondary rounded-lg hover:bg-slate-100">
            <span class="material-symbols-outlined">menu</span>
        </button>

        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 shrink-0">
            <span class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center font-extrabold text-white text-xs">HA</span>
            <span class="font-bold text-[15px] tracking-tight text-slate-900 hidden sm:block">Hello Alibaug</span>
        </a>

        {{-- Global search --}}
        <form action="{{ route('admin.listings.index') }}" method="GET" class="flex-1 max-w-xl mx-auto hidden sm:block">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search listings…"
                       class="w-full bg-slate-100 border border-transparent focus:border-primary focus:bg-white rounded-lg pl-10 pr-3 py-2 text-sm text-slate-700 focus:ring-2 focus:ring-primary/15 outline-none transition-colors">
            </div>
        </form>

        <div class="ml-auto flex items-center gap-1.5">
            <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 text-[13px] font-semibold text-text-secondary hover:text-primary px-3 py-2 rounded-lg hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span> View site
            </a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <img src="{{ auth()->user()->getAvatarUrl() }}" class="w-8 h-8 rounded-lg object-cover" alt="">
                    <span class="text-[13px] font-semibold text-slate-700 hidden sm:block">{{ Str::of(auth()->user()->name)->explode(' ')->first() }}</span>
                    <span class="material-symbols-outlined text-slate-400 text-[18px] hidden sm:block">expand_more</span>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl border border-border-light py-2 z-50 origin-top-right">
                    <div class="px-4 py-2 border-b border-slate-100 mb-1">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"><span class="material-symbols-outlined text-[18px] text-slate-400">storefront</span> View site</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"><span class="material-symbols-outlined text-[18px]">logout</span> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div>
        {{-- ── Left navigation ──────────────────────────────── --}}
        <aside class="fixed top-14 left-0 z-30 h-[calc(100vh-3.5rem)] w-60 bg-white border-r border-border-light overflow-y-auto transform transition-transform duration-200 md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <nav class="px-3 py-4 space-y-6">
                <div>
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.14em] mb-1.5">Main</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.dashboard') }}" class="{{ $navBase }} {{ request()->routeIs('admin.dashboard') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">dashboard</span> Overview
                        </a>
                        <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" class="{{ $navBase }} {{ request()->routeIs('admin.listings.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">approval</span> Approval Queue
                            @php $pc = \App\Models\Listing::pending()->count(); @endphp
                            @if($pc > 0)<span class="{{ $badge }} bg-amber-100 text-amber-700">{{ $pc }}</span>@endif
                        </a>
                        <a href="{{ route('admin.classifieds.index', ['status' => 'pending']) }}" class="{{ $navBase }} {{ request()->routeIs('admin.classifieds.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">storefront</span> Marketplace
                            @php $mc = \App\Models\Classified::pending()->count(); @endphp
                            @if($mc > 0)<span class="{{ $badge }} bg-amber-100 text-amber-700">{{ $mc }}</span>@endif
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.categories.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">category</span> Categories
                        </a>
                        <a href="{{ route('admin.areas.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.areas.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">location_on</span> Areas
                        </a>
                        <a href="{{ route('admin.tags.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.tags.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">local_offer</span> Listing Tags
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.14em] mb-1.5">Management</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.users.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.users.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">group</span> User Control
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.reviews.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">reviews</span> Reviews
                            @php $pr = \App\Models\Review::pending()->count(); @endphp
                            @if($pr > 0)<span class="{{ $badge }} bg-amber-100 text-amber-700">{{ $pr }}</span>@endif
                        </a>
                        <a href="{{ route('admin.support.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.support.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">support_agent</span> Support Tickets
                            @php $st = \App\Models\SupportTicket::active()->count(); @endphp
                            @if($st > 0)<span class="{{ $badge }} bg-amber-100 text-amber-700">{{ $st }}</span>@endif
                        </a>
                        <a href="{{ route('admin.inquiries.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.inquiries.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">mail</span> Inquiries
                            @php $ni = \App\Models\Inquiry::where('status', 'new')->count(); @endphp
                            @if($ni > 0)<span class="{{ $badge }} bg-blue-100 text-blue-700">{{ $ni }}</span>@endif
                        </a>
                        <a href="{{ route('admin.seo.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.seo.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">search</span> SEO Manager
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.14em] mb-1.5">Blog</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.blog.posts.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.blog.posts.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">article</span> Posts
                        </a>
                        <a href="{{ route('admin.blog.categories.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.blog.categories.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">category</span> Categories
                        </a>
                        <a href="{{ route('admin.blog.tags.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.blog.tags.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">tag</span> Tags
                        </a>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-[0.14em] mb-1.5">Editorial</p>
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.guides.index') }}" class="{{ $navBase }} {{ request()->routeIs('admin.guides.*') ? $navActive : $navIdle }}">
                            <span class="{{ $navIcon }}">menu_book</span> Guides
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 top-14 bg-black/40 z-20 md:hidden" x-transition.opacity x-cloak></div>

        {{-- ── Content ──────────────────────────────────────── --}}
        <main class="md:ml-60 min-h-[calc(100vh-3.5rem)]">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6">
                {{-- Polaris-style page header --}}
                @hasSection('page-title')
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900">@yield('page-title')</h1>
                        <div class="flex items-center gap-2">@yield('page-actions')</div>
                    </div>
                @endif

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                         class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                         class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-red-600">error</span>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>

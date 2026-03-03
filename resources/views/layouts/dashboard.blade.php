<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background-light text-text-main font-display antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-border-light transform transition-transform duration-200 md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                {{-- Logo --}}
                <div class="flex items-center gap-2 px-6 h-16 border-b border-border-light">
                    <a href="{{ route('home') }}" class="flex items-center gap-1">
                        <span class="text-lg font-bold text-primary">Hello</span>
                        <span class="text-lg font-bold text-charcoal">Alibaug</span>
                    </a>
                </div>

                {{-- User Info --}}
                <div class="px-6 py-4 border-b border-border-light">
                    <div class="flex items-center gap-3">
                        <img src="{{ auth()->user()->getAvatarUrl() }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-semibold text-text-main">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-text-secondary">{{ ucfirst(auth()->user()->role->name ?? 'Owner') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Nav Links --}}
                <nav class="flex-1 px-3 py-4 space-y-1">
                    <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.dashboard') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">dashboard</span>
                        Dashboard
                    </a>
                    <a href="{{ route('owner.listings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.listings.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">list</span>
                        My Listings
                    </a>
                    <a href="{{ route('owner.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.reviews.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">reviews</span>
                        Reviews
                    </a>
                    <a href="{{ route('owner.inquiries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.inquiries.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">mail</span>
                        Inquiries
                        @php $newInq = \App\Models\Inquiry::whereIn('listing_id', auth()->user()->listings()->pluck('id'))->where('status', 'new')->count(); @endphp
                        @if($newInq > 0)
                            <span class="ml-auto bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $newInq }}</span>
                        @endif
                    </a>
                    <a href="{{ route('owner.onboarding.start') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.onboarding.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Add Listing
                    </a>
                    <a href="{{ route('owner.support.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('owner.support.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-background-light' }}">
                        <span class="material-symbols-outlined text-[20px]">support_agent</span>
                        Support
                        @php $openTickets = \App\Models\SupportTicket::where('user_id', auth()->id())->active()->count(); @endphp
                        @if($openTickets > 0)
                            <span class="ml-auto bg-primary text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $openTickets }}</span>
                        @endif
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-background-light">
                        <span class="material-symbols-outlined text-[20px]">person</span>
                        Profile
                    </a>
                </nav>

                {{-- Logout --}}
                <div class="px-3 py-4 border-t border-border-light">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-red-50 hover:text-red-600 w-full">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition.opacity></div>

        {{-- Main Content --}}
        <div class="flex-1 md:ml-64">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-border-light h-16 flex items-center px-4 sm:px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-text-secondary mr-3">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-lg font-semibold text-text-main">@yield('page-title', 'Dashboard')</h1>
                <div class="ml-auto flex items-center gap-3">
                    <a href="{{ route('home') }}" class="text-sm text-text-secondary hover:text-primary">
                        <span class="material-symbols-outlined text-[20px]">home</span>
                    </a>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mx-4 sm:mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-6">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>

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
</head>
<body class="bg-background-light text-text-main font-display antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        {{-- Admin Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-charcoal text-white transform transition-transform duration-200 md:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex flex-col h-full">
                <div class="flex items-center gap-2 px-6 h-16 border-b border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1">
                        <span class="text-lg font-bold text-primary">Hello</span>
                        <span class="text-lg font-bold text-white">Alibaug</span>
                    </a>
                    <span class="text-xs bg-primary/20 text-primary px-2 py-0.5 rounded-full font-medium ml-1">Admin</span>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-8">
                    {{-- Main Section --}}
                    <div>
                        <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Main</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                Overview
                            </a>
                            <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.listings.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">approval</span>
                                Approval Queue
                                @php $pc = \App\Models\Listing::pending()->count(); @endphp
                                @if($pc > 0)
                                    <span class="ml-auto bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $pc }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">category</span>
                                Categories
                            </a>
                            <a href="{{ route('admin.areas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.areas.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">location_on</span>
                                Areas
                            </a>
                        </div>
                    </div>

                    {{-- Management Section --}}
                    <div>
                        <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Management</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">group</span>
                                User Control
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reviews.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">reviews</span>
                                Reviews
                                @php $pr = \App\Models\Review::pending()->count(); @endphp
                                @if($pr > 0)
                                    <span class="ml-auto bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $pr }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.support.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.support.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">support_agent</span>
                                Support Tickets
                                @php $st = \App\Models\SupportTicket::active()->count(); @endphp
                                @if($st > 0)
                                    <span class="ml-auto bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $st }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.inquiries.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                                Inquiries
                                @php $ni = \App\Models\Inquiry::where('status', 'new')->count(); @endphp
                                @if($ni > 0)
                                    <span class="ml-auto bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $ni }}</span>
                                @endif
                            </a>
                            <a href="{{ route('admin.seo.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.seo.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                                SEO Manager
                            </a>
                        </div>
                    </div>
                </nav>

                <div class="px-3 py-4 border-t border-gray-700">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-white/5 hover:text-white">
                        <span class="material-symbols-outlined text-[20px]">home</span>
                        View Site
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-red-900/50 hover:text-red-300 w-full">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden" x-transition.opacity></div>

        <div class="flex-1 md:ml-64">
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-border-light h-16 flex items-center px-4 sm:px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 text-text-secondary mr-3">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-lg font-semibold text-text-main">@yield('page-title', 'Admin Panel')</h1>
            </header>

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

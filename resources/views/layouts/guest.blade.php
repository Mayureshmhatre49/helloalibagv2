<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hello Alibaug') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Noto+Serif+Display:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-display text-slate-900 antialiased">
    <div class="min-h-screen flex">
        {{-- Left: Image Panel --}}
        <div class="hidden lg:flex lg:w-1/2 relative">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDCjXJ9SdsU7gHAoMBh-JX1MWxPHHkE3WrHJRSbazkXoRB7JU6ktFz0SEFYSQDEpdPwmP4wuTxuHmxRIPLXekcAGVML9IH3fFdaq8Ap2Q0nh9G_PmOSstoRAAo4N6LClAMQVX-X4n6r19vZWKy4nsuSH3wcAVJ5QZ8bLHvq50lCfZcYnkytR9wkq-3JN8ld2hJAaA1jAwNOoFMx0ttBb83vl4Tsm8GDKyswgf1iI55Uvou1CSNfTxLvm3PrLufWPXg1I1-KutRovQ" alt="Alibaug coastline" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-12">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center shadow-lg"
                         style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%); box-shadow: 0 4px 16px rgba(232,160,32,0.5);">
                        <span class="material-symbols-outlined text-white text-[24px]" style="font-variation-settings:'FILL' 1">sailing</span>
                    </div>
                    <div class="leading-none">
                        <p class="text-white font-extrabold text-xl tracking-tight leading-none">Hello <span style="color:#f5c842;">Alibaug</span></p>
                        <p class="text-white/55 text-[10px] tracking-widest uppercase mt-2 font-medium">Discover · Stay · Eat</p>
                    </div>
                </a>
                <h2 class="text-3xl font-serif font-bold text-white mb-3 leading-tight">Experience the finest of<br>coastal living</h2>
                <p class="text-white/80 text-base max-w-md">Discover luxury stays, premium real estate, and curated dining experiences along the Konkan coast.</p>
                <div class="mt-8 flex gap-6">
                    <div>
                        <p class="text-2xl font-bold text-white">{{ \App\Models\Listing::count() > 0 ? \App\Models\Listing::count() . '+' : '50+' }}</p>
                        <p class="text-white/60 text-sm">Premium listings</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ \App\Models\User::count() > 0 ? \App\Models\User::count() . '+' : '100+' }}</p>
                        <p class="text-white/60 text-sm">Happy guests</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">4.8★</p>
                        <p class="text-white/60 text-sm">Average rating</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Form Panel --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white">
            {{-- Mobile Logo --}}
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow"
                         style="background: linear-gradient(135deg, #e8a020 0%, #f5c842 100%);">
                        <span class="material-symbols-outlined text-white text-[20px]" style="font-variation-settings:'FILL' 1">sailing</span>
                    </div>
                    <div class="leading-none">
                        <p class="text-slate-900 font-extrabold text-base tracking-tight leading-none">Hello <span class="text-amber-500">Alibaug</span></p>
                        <p class="text-slate-400 text-[10px] tracking-widest uppercase mt-2 font-medium">Discover · Stay · Eat</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hello Alibaug') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

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
            <img src="{{ asset('images/auth-side-bg.jpg') }}" alt="Casa Frangipani Villa" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/90 via-slate-900/60 to-slate-900/40 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-transparent to-transparent"></div>
            {{-- Logo at the top --}}
            <div class="absolute top-12 left-12 z-10">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/helloalibaug-logo.png') }}" alt="Hello Alibaug — Discover, Stay, Eat" class="h-12 w-auto">
                </a>
            </div>

            {{-- Text content at the bottom --}}
            <div class="absolute inset-0 flex flex-col justify-end p-12">
                <h2 class="text-3xl font-serif font-bold text-white mb-3 leading-tight">Experience the finest of<br>coastal living</h2>
                <p class="text-white/80 text-base max-w-md">Discover luxury stays, premium real estate, and curated dining experiences along the Konkan coast.</p>
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
                        <p class="text-slate-500 text-[10px] tracking-widest uppercase mt-2 font-medium">Discover · Stay · Eat</p>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

@extends('layouts.app')
@section('title', 'Page Not Found')
@section('meta_desc', 'The page you are looking for could not be found.')

@section('content')
<main class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto px-6 py-20 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary/10 text-primary mb-6">
            <span class="material-symbols-outlined text-4xl">search_off</span>
        </div>
        <h1 class="text-6xl font-serif font-bold text-slate-900 mb-3">404</h1>
        <h2 class="text-xl font-bold text-slate-700 mb-3">Page Not Found</h2>
        <p class="text-slate-500 mb-8">Looks like this page wandered off into the sea. Let's get you back to Alibaug.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-[18px]">home</span> Back to Home
            </a>
            <a href="{{ route('search') }}"
               class="inline-flex items-center gap-2 border border-slate-200 bg-white text-slate-700 px-6 py-3 rounded-xl font-bold hover:border-primary hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">search</span> Search Listings
            </a>
        </div>
    </div>
</main>
@endsection

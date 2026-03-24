@extends('layouts.app')
@section('title', 'Something Went Wrong')
@section('meta_desc', 'An unexpected error occurred. Please try again.')

@section('content')
<main class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto px-6 py-20 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 text-red-500 mb-6">
            <span class="material-symbols-outlined text-4xl">error</span>
        </div>
        <h1 class="text-6xl font-serif font-bold text-slate-900 mb-3">500</h1>
        <h2 class="text-xl font-bold text-slate-700 mb-3">Something Went Wrong</h2>
        <p class="text-slate-500 mb-8">Our team has been notified and is looking into it. Please try again in a few moments.</p>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[18px]">home</span> Back to Home
        </a>
    </div>
</main>
@endsection

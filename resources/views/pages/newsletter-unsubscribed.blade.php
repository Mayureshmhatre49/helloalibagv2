@extends('layouts.app')
@section('title', 'Unsubscribed')

@section('content')
<main class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-lg mx-auto px-6 py-20 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-200 text-slate-500 mb-6">
            <span class="material-symbols-outlined text-4xl">unsubscribe</span>
        </div>
        <h1 class="text-3xl font-serif font-bold text-slate-900 mb-3">You've Been Unsubscribed</h1>
        <p class="text-slate-500 mb-8">You won't receive any more emails from Hello Alibaug Insights. You can resubscribe any time from the homepage.</p>

        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[18px]">home</span> Back to Home
        </a>
    </div>
</main>
@endsection

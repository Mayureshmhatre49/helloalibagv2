@extends('layouts.app')
@section('title', 'About Us — Hello Alibaug')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif font-bold text-text-main mb-4">About Hello Alibaug</h1>
        <p class="text-lg text-text-secondary max-w-2xl mx-auto">Connecting travelers with the finest experiences in Alibaug — from luxury stays to authentic local dining and unforgettable adventures.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white rounded-2xl border border-border-light p-8">
            <span class="material-symbols-outlined text-primary text-3xl mb-4 block">visibility</span>
            <h3 class="text-lg font-bold text-text-main mb-2">Our Vision</h3>
            <p class="text-text-secondary text-sm leading-relaxed">To become the #1 discovery platform for Alibaug, making it effortless for visitors to find and book the best stays, restaurants, activities, and real estate the coast has to offer.</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-8">
            <span class="material-symbols-outlined text-primary text-3xl mb-4 block">handshake</span>
            <h3 class="text-lg font-bold text-text-main mb-2">Our Promise</h3>
            <p class="text-text-secondary text-sm leading-relaxed">Every listing on Hello Alibaug is verified for quality. We work closely with local business owners to ensure you have the most accurate information and a seamless experience.</p>
        </div>
    </div>

    <div class="bg-charcoal rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-serif font-bold mb-4">Want to List Your Business?</h2>
        <p class="text-slate-300 mb-6 max-w-xl mx-auto">Join our growing network of premium businesses in Alibaug and reach thousands of potential customers.</p>
        <a href="{{ route('register') }}" class="inline-flex bg-primary text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-dark transition-colors">Get Started Free</a>
    </div>
</div>
@endsection

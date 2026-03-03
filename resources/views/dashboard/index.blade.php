@extends('layouts.dashboard')
@section('page-title', 'Dashboard')

@section('content')

@if($totalListings == 0)
    <div class="bg-white rounded-3xl border border-border-light p-12 text-center max-w-2xl mx-auto mt-10 shadow-sm">
        <div class="size-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl">storefront</span>
        </div>
        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-4">Welcome to Hello Alibaug!</h2>
        <p class="text-slate-500 mb-8 text-lg">You don't have any listings yet. Create your first listing to start reaching thousands of visitors.</p>
        <a href="{{ route('owner.onboarding.start') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-primary/20 text-lg">
            <span class="material-symbols-outlined font-normal">add_circle</span>
            Add Your First Listing
        </a>
    </div>
@elseif($totalListings > 0 && $approvedListings == 0)
    <div class="bg-white rounded-3xl border border-border-light p-12 text-center max-w-2xl mx-auto mt-10 shadow-sm">
        <div class="size-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl">hourglass_empty</span>
        </div>
        <h2 class="text-3xl font-serif font-bold text-slate-900 mb-4">Registration Complete!</h2>
        <p class="text-slate-500 mb-8 text-lg">Your first listing is currently pending approval. Our team will review it shortly. Once approved, your full dashboard will unlock and your listing will be live!</p>
        <a href="{{ route('owner.listings.index') }}" class="inline-flex items-center gap-2 bg-background-light text-text-main px-8 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
            View My Listings
        </a>
    </div>
@else
    {{-- Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">list</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $totalListings }}</p>
            <p class="text-sm text-text-secondary">Total Listings</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-green-600 bg-green-50 p-2 rounded-xl">check_circle</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $approvedListings }}</p>
            <p class="text-sm text-text-secondary">Approved</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-amber-600 bg-amber-50 p-2 rounded-xl">pending</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $pendingListings }}</p>
            <p class="text-sm text-text-secondary">Pending Review</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-blue-600 bg-blue-50 p-2 rounded-xl">visibility</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ number_format($totalViews) }}</p>
            <p class="text-sm text-text-secondary">Total Views</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-border-light p-6">
        <h2 class="text-lg font-semibold text-text-main mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('owner.onboarding.start') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                Add New Listing
            </a>
            <a href="{{ route('owner.listings.index') }}" class="flex items-center gap-2 bg-background-light text-text-main px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-[20px]">list</span>
                View My Listings
            </a>
        </div>
    </div>
@endif
@endsection

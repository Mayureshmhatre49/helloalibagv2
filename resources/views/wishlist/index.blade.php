@extends('layouts.app')
@section('title', 'My Wishlist')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-serif font-bold text-text-main mb-2">My Wishlist</h1>
        <p class="text-text-secondary">Listings you've saved for later.</p>
    </div>

    @if($wishlistItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
                @include('components.listing-card', ['listing' => $item->listing])
            @endforeach
        </div>
        <div class="mt-8">{{ $wishlistItems->links() }}</div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl border border-border-light">
            <div class="size-16 bg-red-50 text-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">favorite</span>
            </div>
            <p class="text-text-main font-semibold mb-1">Your wishlist is empty</p>
            <p class="text-sm text-text-secondary mb-5">Browse listings and tap the heart icon to save them here.</p>
            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Browse Listings
            </a>
        </div>
    @endif
</div>
@endsection

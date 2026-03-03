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
        <p class="text-slate-500 mb-8 text-lg">Your listing is currently pending approval. Our team will review it shortly.</p>
        <a href="{{ route('owner.listings.index') }}" class="inline-flex items-center gap-2 bg-background-light text-text-main px-8 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
            View My Listings
        </a>
    </div>
@else
    {{-- Metrics Row 1 --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-xl">list</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $totalListings }}</p>
            <p class="text-sm text-text-secondary">Total Listings</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-blue-600 bg-blue-50 p-2 rounded-xl">visibility</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ number_format($totalViews) }}</p>
            <p class="text-sm text-text-secondary">Total Views</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-purple-600 bg-purple-50 p-2 rounded-xl">mail</span>
                @if($newInquiries > 0)
                    <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $newInquiries }} new</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $totalInquiries }}</p>
            <p class="text-sm text-text-secondary">Inquiries</p>
        </div>
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="material-symbols-outlined text-amber-500 bg-amber-50 p-2 rounded-xl">star</span>
            </div>
            <p class="text-2xl font-bold text-text-main">{{ $avgRating ? number_format($avgRating, 1) : '—' }}</p>
            <p class="text-sm text-text-secondary">Avg Rating ({{ $totalReviews }} reviews)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Listing --}}
        @if($topListing)
        <div class="bg-white rounded-2xl border border-border-light p-6">
            <h3 class="text-sm font-bold text-text-main mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[18px]">trending_up</span>
                Top Performing Listing
            </h3>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($topListing->getPrimaryImageUrl())
                        <img src="{{ $topListing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><span class="material-symbols-outlined">image</span></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-text-main truncate">{{ $topListing->title }}</h4>
                    <div class="flex items-center gap-4 mt-1 text-xs text-text-secondary">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">visibility</span> {{ number_format($topListing->views_count) }} views</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">star</span> {{ $topListing->getAverageRating() ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Recent Inquiries --}}
        <div class="bg-white rounded-2xl border border-border-light overflow-hidden">
            <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
                <h3 class="text-sm font-bold text-text-main flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500 text-[18px]">mail</span>
                    Recent Inquiries
                </h3>
                <a href="{{ route('owner.inquiries.index') }}" class="text-xs text-primary font-medium hover:underline">View All</a>
            </div>
            <div class="divide-y divide-border-light">
                @forelse($recentInquiries as $inq)
                    <a href="{{ route('owner.inquiries.show', $inq) }}" class="flex items-center justify-between px-6 py-3 hover:bg-background-light/50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-text-main">{{ $inq->name }}</p>
                            <p class="text-xs text-text-secondary">{{ $inq->listing->title }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $inq->getStatusBadgeClass() }}">{{ $inq->getStatusLabel() }}</span>
                            <p class="text-[10px] text-text-secondary mt-0.5">{{ $inq->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-text-secondary">No inquiries yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-border-light p-6">
        <h2 class="text-sm font-bold text-text-main mb-3">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('owner.onboarding.start') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[20px]">add_circle</span> Add New Listing
            </a>
            <a href="{{ route('owner.listings.index') }}" class="flex items-center gap-2 bg-background-light text-text-main px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-[20px]">list</span> View My Listings
            </a>
            <a href="{{ route('owner.support.create') }}" class="flex items-center gap-2 bg-background-light text-text-main px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                <span class="material-symbols-outlined text-[20px]">support_agent</span> Get Support
            </a>
        </div>
    </div>
@endif
@endsection

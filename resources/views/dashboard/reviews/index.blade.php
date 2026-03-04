@extends('layouts.dashboard')
@section('page-title', 'My Reviews')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-text-main">Customer Feedback</h2>
</div>

{{-- Overview Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-border-light p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-amber-500 text-2xl">star</span>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary">Average Rating</p>
                <p class="text-2xl font-bold text-text-main">{{ number_format($averageRating, 1) }} / 5.0</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl border border-border-light p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-blue-500 text-2xl">reviews</span>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary">Total Approved Reviews</p>
                <p class="text-2xl font-bold text-text-main">{{ number_format($totalReviews) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-border-light overflow-hidden shadow-sm">
    {{-- Filters --}}
    <div class="px-6 py-5 border-b border-border-light bg-background-light/50">
        <form action="{{ route('owner.reviews.index') }}" method="GET" class="flex items-center gap-4">
            <label class="text-sm font-medium text-text-secondary">Filter by Rating:</label>
            <select name="rating" onchange="this.form.submit()" class="pl-4 pr-10 py-2 rounded-xl border-border-light text-sm focus:border-primary focus:ring-primary bg-white">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
            </select>
            @if(request()->filled('rating'))
                <a href="{{ route('owner.reviews.index') }}" class="text-xs font-bold text-text-secondary hover:text-primary">Clear Filter</a>
            @endif
        </form>
    </div>

    {{-- Reviews List --}}
    <div class="divide-y divide-border-light">
        @forelse($reviews as $review)
            <div class="p-6 hover:bg-background-light/30 transition-colors">
                <div class="flex flex-col sm:flex-row gap-5">
                    <div class="flex-shrink-0">
                        <img src="{{ optional($review->user)->getAvatarUrl() }}" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <div>
                                <h3 class="font-bold text-text-main">{{ optional($review->user)->name ?? 'Anonymous' }}</h3>
                                <div class="flex items-center gap-2 text-xs text-text-secondary">
                                    <span class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined {{ $i <= $review->rating ? 'text-amber-400 filled' : 'text-slate-200' }}" style="font-size: 14px;">star</span>
                                        @endfor
                                    </span>
                                    <span>•</span>
                                    <span>{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @if($review->status === 'pending')
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Pending Approval</span>
                            @elseif($review->status === 'rejected')
                                <span class="bg-red-100 text-red-700 text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider">Rejected by Admin</span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-text-secondary leading-relaxed mb-3">
                            "{{ $review->comment }}"
                        </p>
                        
                        <div class="bg-background-light rounded-lg p-3 inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-400 text-[16px]">storefront</span>
                            <span class="text-xs text-text-secondary">Reviewed on: </span>
                            <a href="{{ route('listing.show', [$review->listing->category->slug, $review->listing->slug]) }}" target="_blank" class="text-xs font-bold text-primary hover:underline">
                                {{ $review->listing->title }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-background-light rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-slate-300 text-3xl">reviews</span>
                </div>
                <h3 class="font-bold text-text-main mb-1">No Reviews Yet</h3>
                <p class="text-sm text-text-secondary max-w-sm mx-auto">When customers leave reviews on your listings, they will appear here. Provide great service to encourage positive ratings!</p>
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-border-light bg-background-light/30">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection

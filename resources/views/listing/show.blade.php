@extends('layouts.app')
@section('title', $listing->title . ' — ' . $listing->category->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-text-secondary mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('category.show', $listing->category) }}" class="hover:text-primary">{{ $listing->category->name }}</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-text-main">{{ $listing->title }}</span>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Image Gallery --}}
            <div class="rounded-2xl overflow-hidden mb-8 bg-gray-100" x-data="{ activeImg: 0 }">
                <div class="aspect-[16/9] relative">
                    @foreach($listing->images as $idx => $image)
                        <img x-show="activeImg === {{ $idx }}" src="{{ $image->path }}" alt="{{ $image->alt_text ?? $listing->title }}" class="absolute inset-0 w-full h-full object-cover" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @endforeach
                    @if($listing->images->isEmpty())
                        <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-6xl text-gray-300">image</span></div>
                    @endif
                </div>
                @if($listing->images->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto hide-scrollbar">
                        @foreach($listing->images as $idx => $image)
                            <button @click="activeImg = {{ $idx }}" :class="activeImg === {{ $idx }} ? 'ring-2 ring-primary' : 'opacity-60 hover:opacity-100'" class="flex-shrink-0 w-20 h-14 rounded-lg overflow-hidden transition-all">
                                <img src="{{ $image->path }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Title & Meta --}}
            <div class="mb-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-text-main mb-2">{{ $listing->title }}</h1>
                        <div class="flex items-center flex-wrap gap-3 text-sm text-text-secondary">
                            @if($listing->area)
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">location_on</span> {{ $listing->area->name }}</span>
                            @endif
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">category</span> {{ $listing->category->name }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">visibility</span> {{ number_format($listing->views_count) }} views</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 bg-amber-50 px-3 py-1.5 rounded-lg">
                        <span class="material-symbols-outlined filled text-amber-400 text-lg">star</span>
                        <span class="font-bold text-text-main">{{ $listing->getAverageRating() ?: '—' }}</span>
                        <span class="text-xs text-text-secondary">({{ $listing->getReviewsCount() }})</span>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-2xl border border-border-light p-6 mb-6">
                <h2 class="text-lg font-semibold text-text-main mb-3">About</h2>
                <div class="text-text-secondary text-sm leading-relaxed prose max-w-none">
                    {!! nl2br(e($listing->description)) !!}
                </div>
            </div>

            {{-- Dynamic Attributes --}}
            @php $attrs = $listing->getDynamicAttributes(); @endphp
            @if(!empty($attrs))
                <div class="bg-white rounded-2xl border border-border-light p-6 mb-6">
                    <h2 class="text-lg font-semibold text-text-main mb-3">Details</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($attrs as $key => $value)
                            <div class="bg-background-light rounded-xl p-3">
                                <p class="text-xs text-text-secondary mb-1 capitalize">{{ str_replace('_', ' ', $key) }}</p>
                                <p class="font-semibold text-text-main text-sm">{{ $value }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Amenities --}}
            @if($listing->amenities->count() > 0)
                <div class="bg-white rounded-2xl border border-border-light p-6 mb-6">
                    <h2 class="text-lg font-semibold text-text-main mb-3">Amenities</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($listing->amenities as $amenity)
                            <div class="flex items-center gap-3 p-3 bg-background-light rounded-xl">
                                <span class="material-symbols-outlined text-primary text-[20px]">{{ $amenity->icon }}</span>
                                <span class="text-sm font-medium text-text-main">{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            <div class="bg-white rounded-2xl border border-border-light p-6 mb-8" id="reviews">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-text-main">Reviews ({{ $listing->getReviewsCount() }})</h2>
                </div>

                @auth
                    @if(auth()->id() !== $listing->created_by && !$listing->reviews()->where('user_id', auth()->id())->exists())
                        <div class="bg-background-light rounded-xl p-5 mb-8">
                            <h3 class="font-bold text-text-main mb-3">Leave a Review</h3>
                            <form action="{{ route('listing.review.store', $listing) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-text-secondary mb-1">Rating</label>
                                    <select name="rating" required class="w-full sm:w-1/3 rounded-lg border-border-light text-text-main focus:ring-primary focus:border-primary">
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Very Good</option>
                                        <option value="3">3 - Average</option>
                                        <option value="2">2 - Poor</option>
                                        <option value="1">1 - Terrible</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-text-secondary mb-1">Review</label>
                                    <textarea name="comment" required rows="3" class="w-full rounded-lg border-border-light text-text-main focus:ring-primary focus:border-primary" placeholder="Share your experience..."></textarea>
                                </div>
                                <button type="submit" class="bg-primary text-white font-medium px-6 py-2 rounded-lg hover:bg-primary-dark transition-colors border-2 border-primary shadow-sm">Submit Review</button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="bg-amber-50 rounded-xl p-4 mb-8 flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-500">info</span>
                        <p class="text-sm text-text-secondary">Please <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">log in</a> to leave a review.</p>
                    </div>
                @endauth
                
                <div class="space-y-6">
                    @forelse($listing->approvedReviews as $review)
                        <div class="flex gap-4 {{ !$loop->last ? 'pb-6 border-b border-border-light' : '' }}">
                            <img src="{{ $review->user->getAvatarUrl() }}" alt="{{ $review->user->name }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0 border border-border-light">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-text-main">{{ $review->user->name }}</span>
                                    <p class="text-xs text-text-secondary">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center gap-0.5 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined {{ $i <= $review->rating ? 'filled text-amber-400' : 'text-gray-200' }}" style="font-size: 16px;">star</span>
                                    @endfor
                                </div>
                                <p class="text-sm text-text-secondary leading-relaxed">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">reviews</span>
                            <p class="text-sm text-text-secondary">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:w-80 flex-shrink-0">
            <div class="lg:sticky lg:top-24 space-y-4">
                {{-- Price Card --}}
                <div class="bg-white rounded-2xl border border-border-light p-6">
                    @if($listing->price)
                        <div class="text-2xl font-bold text-text-main mb-1">₹{{ number_format($listing->price) }}</div>
                        <p class="text-sm text-text-secondary mb-4">per night</p>
                    @else
                        <p class="text-lg font-semibold text-text-main mb-4">Contact for pricing</p>
                    @endif

                    @if($listing->phone)
                        <a href="tel:{{ $listing->phone }}" class="flex items-center justify-center gap-2 w-full bg-primary text-white py-3 rounded-xl font-medium text-sm hover:bg-primary-dark transition-colors mb-2">
                            <span class="material-symbols-outlined text-[20px]">call</span>
                            Call Now
                        </a>
                    @endif
                    @if($listing->whatsapp)
                        <a href="https://wa.me/91{{ $listing->whatsapp }}" target="_blank" class="flex items-center justify-center gap-2 w-full bg-green-600 text-white py-3 rounded-xl font-medium text-sm hover:bg-green-700 transition-colors mb-2">
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                            WhatsApp
                        </a>
                    @endif
                    @if($listing->email)
                        <a href="mailto:{{ $listing->email }}" class="flex items-center justify-center gap-2 w-full bg-white border border-border-light text-text-main py-3 rounded-xl font-medium text-sm hover:bg-background-light transition-colors">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                            Email
                        </a>
                    @endif
                </div>

                {{-- Owner Card --}}
                <div class="bg-white rounded-2xl border border-border-light p-5">
                    <h3 class="text-sm font-semibold text-text-main mb-3">Listed by</h3>
                    <div class="flex items-center gap-3">
                        <img src="{{ $listing->creator->getAvatarUrl() }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm font-semibold text-text-main">{{ $listing->creator->name }}</p>
                            <p class="text-xs text-text-secondary">Member since {{ $listing->creator->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    {{-- Related Listings --}}
    @if($relatedListings->count() > 0)
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-text-main mb-6">Similar in {{ $listing->category->name }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedListings as $related)
                    @include('components.listing-card', ['listing' => $related])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

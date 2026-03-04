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
            {{-- Image Gallery — Airbnb-style 50/50 --}}
            @php
                $images      = $listing->images;
                $totalImages = $images->count();
                $mainImage   = $images->first();
                $gridImages  = $images->skip(1)->take(4)->values(); // up to 4 for the right grid
                $extraCount  = max(0, $totalImages - 5);            // images beyond the first 5
            @endphp

            <div class="mb-8" x-data="{ current: 0, open: false }"
                 @keydown.escape.window="open = false"
                 @keydown.arrowleft.window="if(open) current = current > 0 ? current - 1 : {{ max(0, $totalImages - 1) }}"
                 @keydown.arrowright.window="if(open) current = current < {{ max(0, $totalImages - 1) }} ? current + 1 : 0">

                @if($totalImages === 0)
                    {{-- No images placeholder --}}
                    <div class="aspect-[16/9] bg-slate-100 rounded-3xl flex flex-col items-center justify-center text-slate-300">
                        <span class="material-symbols-outlined text-6xl mb-2">image</span>
                        <p class="text-sm">No photos yet</p>
                    </div>
                @elseif($totalImages === 1)
                    {{-- Single image — full width --}}
                    <div class="aspect-[16/9] rounded-3xl overflow-hidden cursor-zoom-in" @click="open = true; current = 0">
                        <img src="{{ $mainImage->path }}" alt="{{ $listing->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                @else
                    {{-- Grid gallery --}}
                    <div class="grid grid-cols-2 gap-2 rounded-3xl overflow-hidden" style="height: 480px;">

                        {{-- Left: Main / hero image (50%) --}}
                        <div class="relative cursor-zoom-in overflow-hidden group" @click="open = true; current = 0">
                            <img src="{{ $mainImage->path }}" alt="{{ $listing->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                        </div>

                        {{-- Right: adaptive grid (50%) --}}
                        @if($gridImages->count() === 1)
                            {{-- 2 images total: single image fills entire right half --}}
                            <div class="relative cursor-zoom-in overflow-hidden group" @click="open = true; current = 1">
                                <img src="{{ $gridImages[0]->path }}" alt="{{ $listing->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            </div>

                        @elseif($gridImages->count() === 2)
                            {{-- 3 images total: 2 images stacked vertically --}}
                            <div class="grid grid-rows-2 gap-2">
                                @foreach($gridImages as $i => $image)
                                    <div class="relative cursor-zoom-in overflow-hidden group" @click="open = true; current = {{ $i + 1 }}">
                                        <img src="{{ $image->path }}" alt="{{ $listing->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                                    </div>
                                @endforeach
                            </div>

                        @elseif($gridImages->count() === 3)
                            {{-- 4 images total: 2×2 grid — 3 real images + 4th = "See all" CTA --}}
                            <div class="grid grid-cols-2 grid-rows-2 gap-2">
                                @foreach($gridImages as $i => $image)
                                    <div class="relative cursor-zoom-in overflow-hidden group" @click="open = true; current = {{ $i + 1 }}">
                                        <img src="{{ $image->path }}" alt="{{ $listing->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                                    </div>
                                @endforeach
                                {{-- 4th slot: See all photos CTA --}}
                                <div class="relative cursor-pointer bg-slate-800 hover:bg-slate-700 transition-colors flex flex-col items-center justify-center gap-2 rounded-sm group"
                                     @click="open = true; current = 0">
                                    <span class="material-symbols-outlined text-white text-3xl" style="font-variation-settings:'FILL' 1">grid_view</span>
                                    <span class="text-white text-xs font-bold text-center leading-tight px-2">See all<br>{{ $totalImages }} photos</span>
                                </div>
                            </div>

                        @else
                            {{-- 5+ images total: straight 2×2 grid, 4th may show +N counter --}}
                            <div class="grid grid-cols-2 grid-rows-2 gap-2">
                                @foreach($gridImages as $i => $image)
                                    @php $isLast = ($i === 3); @endphp
                                    <div class="relative cursor-zoom-in overflow-hidden group" @click="open = true; current = {{ $i + 1 }}">
                                        <img src="{{ $image->path }}" alt="{{ $listing->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @if($isLast && $extraCount > 0)
                                            <div class="absolute inset-0 bg-black/55 flex flex-col items-center justify-center text-white backdrop-blur-[1px]">
                                                <span class="material-symbols-outlined text-4xl mb-1" style="font-variation-settings:'FILL' 1">photo_library</span>
                                                <span class="text-3xl font-bold">+{{ $extraCount }}</span>
                                                <span class="text-sm font-medium mt-1 opacity-80">more photos</span>
                                            </div>
                                        @else
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- "View all photos" button --}}
                    <div class="flex justify-end mt-3">
                        <button @click="open = true; current = 0"
                            class="flex items-center gap-2 text-sm font-bold text-slate-700 border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 rounded-xl shadow-sm transition-all">
                            <span class="material-symbols-outlined text-[18px]">grid_view</span>
                            View all {{ $totalImages }} photos
                        </button>
                    </div>
                @endif

                {{-- ═══ Pro Fullscreen Gallery Popup ═══════════════════════════ --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-[60] flex flex-col overflow-hidden">

                    {{-- ── Layer 1: Blurred current photo as full-page background ── --}}
                    <div class="absolute inset-0 z-0" @click="open = false">
                        @foreach($images as $idx => $image)
                            <div x-show="current === {{ $idx }}"
                                 x-transition:enter="transition-opacity ease-out duration-500"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 class="absolute inset-0"
                                 style="background-image: url('{{ $image->path }}'); background-size: cover; background-position: center; filter: blur(28px) saturate(1.4); transform: scale(1.15);">
                            </div>
                        @endforeach
                        {{-- Dark scrim over the blurred bg --}}
                        <div class="absolute inset-0 bg-black/65"></div>
                        {{-- Vignette edges --}}
                        <div class="absolute inset-0" style="background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.7) 100%);"></div>
                    </div>

                    {{-- ── Layer 2: Frosted-glass toolbar ─────────────────────────── --}}
                    <div class="relative z-20 flex items-center justify-between px-5 py-3 flex-shrink-0"
                         style="background: rgba(0,0,0,0.25); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg overflow-hidden border border-white/20 flex-shrink-0">
                                @if($images->first())
                                    <img src="{{ $images->first()->path }}" alt="" class="w-full h-full object-cover opacity-70">
                                @endif
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm leading-none truncate max-w-[220px]">{{ $listing->title }}</p>
                                <p class="text-white/50 text-xs mt-0.5">
                                    <span class="text-white/80 font-bold" x-text="current + 1"></span>
                                    <span> of {{ $totalImages }} photos</span>
                                </p>
                            </div>
                        </div>
                        <button @click="open = false"
                            class="w-9 h-9 rounded-full flex items-center justify-center text-white/70 hover:text-white transition-colors"
                            style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                            <span class="material-symbols-outlined text-[22px]">close</span>
                        </button>
                    </div>

                    {{-- ── Layer 3: Main crisp image ────────────────────────────────── --}}
                    <div class="relative z-10 flex-1 flex items-center justify-center min-h-0 px-16 py-4"
                         @click.self="open = false">

                        @foreach($images as $idx => $image)
                            <img x-show="current === {{ $idx }}"
                                 src="{{ $image->path }}"
                                 alt="{{ $image->alt_text ?? $listing->title }}"
                                 class="max-w-full max-h-full object-contain select-none"
                                 style="border-radius: 12px; box-shadow: 0 32px 80px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.06);"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-[0.96]"
                                 x-transition:enter-end="opacity-100 scale-100">
                        @endforeach

                        {{-- Arrow: Previous --}}
                        <button @click="current = current > 0 ? current - 1 : {{ $totalImages - 1 }}"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full text-white flex items-center justify-center transition-all duration-200 group"
                            style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);">
                            <span class="material-symbols-outlined text-[26px] group-hover:scale-110 transition-transform">chevron_left</span>
                        </button>

                        {{-- Arrow: Next --}}
                        <button @click="current = current < {{ $totalImages - 1 }} ? current + 1 : 0"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full text-white flex items-center justify-center transition-all duration-200 group"
                            style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);">
                            <span class="material-symbols-outlined text-[26px] group-hover:scale-110 transition-transform">chevron_right</span>
                        </button>
                    </div>

                    {{-- ── Layer 4: Frosted-glass thumbnail strip ──────────────────── --}}
                    <div class="relative z-20 flex-shrink-0 px-4 py-3"
                         style="background: rgba(0,0,0,0.30); backdrop-filter: blur(12px); border-top: 1px solid rgba(255,255,255,0.08);">
                        <div class="flex gap-2 justify-center overflow-x-auto scrollbar-none">
                            @foreach($images as $idx => $image)
                                <button @click="current = {{ $idx }}"
                                    class="flex-shrink-0 w-16 h-11 rounded-lg overflow-hidden transition-all duration-200 ring-offset-0"
                                    :class="current === {{ $idx }}
                                        ? 'ring-2 ring-white opacity-100 scale-105'
                                        : 'ring-1 ring-white/20 opacity-40 hover:opacity-75 hover:scale-102'">
                                    <img src="{{ $image->path }}" alt="" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                {{-- Share Buttons --}}
                <div class="flex items-center gap-2 mt-3" x-data="{ copied: false }">
                    <span class="text-xs text-text-secondary font-medium">Share:</span>
                    <a href="https://wa.me/?text={{ urlencode($listing->title . ' — ' . url()->current()) }}" target="_blank" class="w-8 h-8 rounded-lg bg-[#25D366]/10 text-[#25D366] flex items-center justify-center hover:bg-[#25D366]/20 transition-colors" title="WhatsApp">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center hover:bg-blue-500/20 transition-colors" title="Facebook">
                        <span class="material-symbols-outlined text-[16px]">share</span>
                    </a>
                    <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(()=> copied = false, 2000)" class="w-8 h-8 rounded-lg bg-gray-100 text-text-secondary flex items-center justify-center hover:bg-gray-200 transition-colors" title="Copy Link">
                        <span class="material-symbols-outlined text-[16px]" x-text="copied ? 'check' : 'link'">link</span>
                    </button>
                    <span x-show="copied" x-cloak class="text-xs text-green-600 font-medium">Copied!</span>
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

                {{-- Inquiry Form --}}
                <div class="bg-white rounded-2xl border border-border-light p-5" x-data="{ showForm: false }">
                    <button @click="showForm = !showForm" class="w-full flex items-center justify-center gap-2 bg-charcoal text-white py-3 rounded-xl font-medium text-sm hover:bg-charcoal/90 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                        Send Inquiry
                    </button>
                    <div x-show="showForm" x-collapse x-cloak class="mt-4">
                        <form method="POST" action="{{ route('listing.inquiry.store', $listing) }}" class="space-y-3">
                            @csrf
                            <div>
                                <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" required placeholder="Your Name *"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}" required placeholder="Your Email *"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone Number"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="check_in" value="{{ old('check_in') }}" placeholder="Check-in"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                <input type="date" name="check_out" value="{{ old('check_out') }}" placeholder="Check-out"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <input type="number" name="guests" min="1" max="50" value="{{ old('guests') }}" placeholder="Number of guests"
                                       class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                            </div>
                            <div>
                                <textarea name="message" rows="3" required placeholder="Hi, I'm interested in this listing... *"
                                          class="w-full border border-border-light rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                                Send Inquiry
                            </button>
                        </form>
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

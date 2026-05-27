@extends('layouts.app')
@section('title', $classified->title . ' — Marketplace · Alibaug')
@section('meta_description', Str::limit(strip_tags($classified->description ?? $classified->title), 150))

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-text-secondary mb-4 flex items-center gap-1.5 flex-wrap">
        <a href="{{ route('marketplace.index') }}" class="hover:text-primary">Marketplace</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('marketplace.index', ['category' => $classified->category->slug]) }}" class="hover:text-primary">{{ $classified->category->name }}</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-text-main font-medium truncate">{{ $classified->title }}</span>
    </nav>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Left: gallery + details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Gallery --}}
            <div x-data="{ active: '{{ $classified->getPrimaryImageUrl() ?? '' }}' }" class="bg-white rounded-2xl border border-border-light overflow-hidden">
                <div class="relative aspect-[4/3] bg-slate-100">
                    @if($classified->getPrimaryImageUrl())
                        <img :src="active" alt="{{ $classified->title }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-slate-300">
                            <span class="material-symbols-outlined text-5xl">image</span><span class="text-sm">No photo</span>
                        </div>
                    @endif
                    @if($classified->isSold())
                        <div class="absolute inset-0 bg-black/45 flex items-center justify-center">
                            <span class="bg-white text-slate-900 px-6 py-2 rounded-full text-lg font-bold rotate-[-6deg] shadow-lg">SOLD</span>
                        </div>
                    @endif
                </div>
                @if($classified->images->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto">
                        @foreach($classified->images as $img)
                            <button @click="active = '{{ $img->url }}'" class="w-20 h-16 rounded-lg overflow-hidden flex-shrink-0 border-2"
                                    :class="active === '{{ $img->url }}' ? 'border-primary' : 'border-transparent'">
                                <img src="{{ $img->url }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-2xl border border-border-light p-6">
                <h2 class="font-bold text-slate-900 text-lg mb-3">Description</h2>
                @if($classified->description)
                    <p class="text-text-secondary whitespace-pre-line leading-relaxed">{{ $classified->description }}</p>
                @else
                    <p class="text-text-secondary italic">No description provided.</p>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-border-light text-sm">
                    @if($classified->getConditionLabel())
                        <div><p class="text-text-secondary text-xs">Condition</p><p class="font-semibold text-slate-900">{{ $classified->getConditionLabel() }}</p></div>
                    @endif
                    @if($classified->area)
                        <div><p class="text-text-secondary text-xs">Location</p><p class="font-semibold text-slate-900">{{ $classified->area->name }}</p></div>
                    @endif
                    <div><p class="text-text-secondary text-xs">Category</p><p class="font-semibold text-slate-900">{{ $classified->category->name }}</p></div>
                    <div><p class="text-text-secondary text-xs">Listed</p><p class="font-semibold text-slate-900">{{ $classified->created_at->diffForHumans() }}</p></div>
                    <div><p class="text-text-secondary text-xs">Views</p><p class="font-semibold text-slate-900">{{ $classified->views_count }}</p></div>
                </div>
            </div>
        </div>

        {{-- Right: price + contact --}}
        <div class="space-y-4">
            <div class="lg:sticky lg:top-24 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6">
                    <h1 class="font-bold text-slate-900 text-xl leading-tight mb-2">{{ $classified->title }}</h1>
                    <div class="flex items-baseline gap-2 mb-4">
                        @if($classified->price)
                            <span class="text-3xl font-extrabold text-slate-900">₹{{ number_format($classified->price) }}</span>
                            @if($classified->is_negotiable)<span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full">Negotiable</span>@endif
                        @else
                            <span class="text-xl font-bold text-slate-500">Contact for price</span>
                        @endif
                    </div>

                    @if($classified->isSold())
                        <div class="bg-slate-100 text-slate-600 text-center py-3 rounded-xl text-sm font-semibold">This item has been sold.</div>
                    @else
                        @auth
                            <div class="space-y-2">
                                @if($classified->contact_whatsapp)
                                    <a href="https://wa.me/91{{ preg_replace('/\D/', '', $classified->contact_whatsapp) }}?text={{ urlencode('Hi, is this still available? ' . $classified->title . ' — ' . url()->current()) }}"
                                       target="_blank" rel="noopener"
                                       class="flex items-center justify-center gap-2 w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 rounded-xl text-sm font-bold transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">chat</span> WhatsApp seller
                                    </a>
                                @endif
                                @if($classified->contact_phone)
                                    <a href="tel:{{ $classified->contact_phone }}"
                                       class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-xl text-sm font-bold transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">call</span> Call {{ $classified->contact_phone }}
                                    </a>
                                @endif
                                @if(!$classified->contact_whatsapp && !$classified->contact_phone)
                                    <p class="text-sm text-text-secondary text-center">Seller hasn't shared contact details.</p>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full bg-primary text-white py-3 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                                <span class="material-symbols-outlined text-[18px]">lock</span> Log in to contact seller
                            </a>
                        @endauth
                    @endif

                    {{-- Seller --}}
                    <div class="flex items-center gap-3 mt-5 pt-5 border-t border-slate-100">
                        <img src="{{ $classified->seller->getAvatarUrl() }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $classified->seller->name }}</p>
                            <p class="text-xs text-text-secondary">Seller</p>
                        </div>
                    </div>
                </div>

                {{-- Safety note --}}
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-xs text-amber-800 flex gap-2">
                    <span class="material-symbols-outlined text-[18px]">shield</span>
                    <span>Meet in a public place, inspect the item, and never pay in advance. Hello Alibaug only connects buyers and sellers.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- More from seller --}}
    @if($moreFromSeller->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-slate-900 mb-4">More from this seller</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($moreFromSeller as $item)
                    @include('components.classified-card', ['item' => $item])
                @endforeach
            </div>
        </div>
    @endif

    {{-- Related --}}
    @if($relatedItems->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-xl font-bold text-slate-900 mb-4">Similar items in {{ $classified->category->name }}</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedItems as $item)
                    @include('components.classified-card', ['item' => $item])
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

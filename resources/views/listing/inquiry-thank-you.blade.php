@extends('layouts.app')

@section('title', 'Request sent — ' . $listing->title)
@section('meta_description', 'Your enquiry has been sent to the listing owner.')
{{-- A one-off confirmation page carries no SEO value and should never be indexed. --}}
@section('robots', 'noindex, nofollow')

@section('content')
<main class="bg-slate-50 min-h-screen py-12 sm:py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">

        {{-- Confirmation --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-10 text-center">
            <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-emerald-600 text-[34px]" style="font-variation-settings:'FILL' 1">check_circle</span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mb-3">Your request has been sent</h1>
            <p class="text-slate-600 leading-relaxed max-w-md mx-auto">
                We've passed your details straight to
                <strong class="text-slate-900">{{ $listing->title }}</strong>.
                They'll contact you directly to confirm.
            </p>

            {{-- The listing they contacted --}}
            <div class="mt-7 flex items-center gap-4 text-left bg-slate-50 border border-slate-100 rounded-xl p-4">
                <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined">image</span>
                    @if($listing->getPrimaryImageUrl())
                        <img src="{{ $listing->getPrimaryImageUrl() }}" alt="{{ $listing->title }}"
                             class="w-16 h-16 object-cover -ml-16" onerror="this.remove()">
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-slate-900 truncate">{{ $listing->title }}</p>
                    <p class="text-sm text-slate-500 truncate">
                        {{ $listing->category?->name }}@if($listing->area) · {{ $listing->area->name }}@endif
                    </p>
                </div>
            </div>
        </div>

        {{-- What happens next --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 mt-5">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5">What happens next</h2>
            <ol class="space-y-4">
                @foreach ([
                    ['mark_email_read', 'The owner has been notified', 'They received your request by email and in their dashboard the moment you sent it.'],
                    ['schedule', 'They\'ll get back to you', 'Most owners reply within a day. Replies come to the email and phone number you gave.'],
                    ['handshake', 'You arrange the details directly', 'Pricing, availability and payment are agreed between you and the business — Hello Alibaug doesn\'t take payment or commission.'],
                ] as $i => [$icon, $heading, $body])
                    <li class="flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-[19px]">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $heading }}</p>
                            <p class="text-sm text-slate-600 leading-relaxed mt-0.5">{{ $body }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <p class="text-xs text-slate-500 mt-6 pt-5 border-t border-slate-100 flex items-start gap-1.5">
                <span class="material-symbols-outlined text-[15px] mt-px flex-shrink-0">info</span>
                Haven't heard back after a couple of days? Try the phone number on the listing, or
                <a href="{{ route('page.contact') }}" class="text-primary font-semibold hover:underline">let us know</a>.
            </p>
        </div>

        {{-- Onward links --}}
        <div class="grid sm:grid-cols-2 gap-3 mt-5">
            <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}"
               class="flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-primary hover:bg-primary/5 text-slate-800 font-bold text-sm px-5 py-3.5 rounded-xl transition-colors">
                <span class="material-symbols-outlined text-[19px]">arrow_back</span>
                Back to the listing
            </a>
            <a href="{{ route('search', ['category_id' => $listing->category_id]) }}"
               class="flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white font-bold text-sm px-5 py-3.5 rounded-xl transition-colors shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[19px]">explore</span>
                Browse more {{ Str::lower($listing->category?->name ?? 'listings') }}
            </a>
        </div>

    </div>
</main>
@endsection

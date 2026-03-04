@extends('layouts.dashboard')
@section('page-title', 'Inquiry from ' . $inquiry->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('owner.inquiries.index') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Inquiries
        </a>
    </div>

    {{-- Inquiry Details --}}
    <div class="bg-white rounded-2xl border border-border-light p-6 mb-4">
        <div class="flex items-center gap-2 mb-4">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $inquiry->getStatusBadgeClass() }}">
                {{ $inquiry->getStatusLabel() }}
            </span>
            <span class="text-xs text-text-secondary">{{ $inquiry->created_at->format('M d, Y \a\t h:i A') }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs text-text-secondary mb-1">From</p>
                <p class="font-semibold text-text-main">{{ $inquiry->name }}</p>
            </div>
            <div>
                <p class="text-xs text-text-secondary mb-1">Listing</p>
                <p class="font-semibold text-text-main">{{ $inquiry->listing->title }}</p>
            </div>
            <div>
                <p class="text-xs text-text-secondary mb-1">Email</p>
                <a href="mailto:{{ $inquiry->email }}" class="text-primary hover:underline text-sm">{{ $inquiry->email }}</a>
            </div>
            @if($inquiry->phone)
            <div>
                <p class="text-xs text-text-secondary mb-1">Phone</p>
                <a href="tel:{{ $inquiry->phone }}" class="text-primary hover:underline text-sm">{{ $inquiry->phone }}</a>
            </div>
            @endif
            @if($inquiry->check_in)
            <div>
                <p class="text-xs text-text-secondary mb-1">Check-in / Check-out</p>
                <p class="text-sm font-medium text-text-main">{{ $inquiry->check_in->format('M d, Y') }} {{ $inquiry->check_out ? '→ ' . $inquiry->check_out->format('M d, Y') : '' }}</p>
            </div>
            @endif
            @if($inquiry->guests)
            <div>
                <p class="text-xs text-text-secondary mb-1">Guests</p>
                <p class="text-sm font-medium text-text-main">{{ $inquiry->guests }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-border-light pt-4">
            <p class="text-xs text-text-secondary mb-2">Message</p>
            <div class="text-sm text-text-main leading-relaxed whitespace-pre-wrap bg-background-light rounded-xl p-4">{{ $inquiry->message }}</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="mailto:{{ $inquiry->email }}" class="inline-flex items-center gap-1.5 bg-primary text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
            <span class="material-symbols-outlined text-[18px]">mail</span> Email Back
        </a>
        @if($inquiry->phone)
        <a href="tel:{{ $inquiry->phone }}" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-green-700 transition-colors">
            <span class="material-symbols-outlined text-[18px]">call</span> Call
        </a>
        <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-[#25D366] text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-[#1da851] transition-colors">
            <span class="material-symbols-outlined text-[18px]">chat</span> WhatsApp
        </a>
        @endif
    </div>

    {{-- Owner Reply --}}
    @if($inquiry->owner_reply)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-green-600 text-[18px]">reply</span>
                <span class="text-sm font-bold text-green-800">Your Reply</span>
                <span class="text-xs text-green-600 ml-auto">{{ $inquiry->replied_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-green-700 whitespace-pre-wrap">{{ $inquiry->owner_reply }}</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <form method="POST" action="{{ route('owner.inquiries.reply', $inquiry) }}">
                @csrf
                <label class="block text-sm font-semibold text-text-main mb-2">Quick Reply</label>
                <textarea name="owner_reply" rows="4" required
                          class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"
                          placeholder="Write your reply to the inquiry...">{{ old('owner_reply') }}</textarea>
                @error('owner_reply') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <div class="flex justify-end mt-3">
                    <button type="submit" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection

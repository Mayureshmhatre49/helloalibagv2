@extends('layouts.app')
@section('title', 'My Bookings')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">book_online</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
            <p class="text-sm text-slate-500">Track your booking requests and their status</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($bookings as $booking)
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="flex flex-col sm:flex-row">
                {{-- Listing thumbnail --}}
                <div class="sm:w-36 h-28 sm:h-auto flex-shrink-0 bg-slate-100">
                    @if($booking->listing->getPrimaryImageUrl())
                        <img src="{{ $booking->listing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-300 text-4xl">image</span>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="flex-1 p-5">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-bold text-slate-900">{{ $booking->listing->title }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border flex-shrink-0 {{ $booking->getStatusBadgeClass() }}">
                            {{ $booking->getStatusLabel() }}
                        </span>
                    </div>
                    @if($booking->check_in)
                    <p class="text-sm text-slate-500 mb-1">
                        <span class="material-symbols-outlined text-[14px] align-middle">calendar_month</span>
                        {{ $booking->check_in->format('d M Y') }} → {{ $booking->check_out?->format('d M Y') }}
                        · {{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}
                    </p>
                    @endif
                    <p class="text-xs text-slate-400">Requested {{ $booking->created_at->diffForHumans() }}</p>

                    @if($booking->owner_notes)
                    <div class="mt-3 bg-slate-50 rounded-xl p-3 text-sm text-slate-600 border border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Owner's note:</p>
                        {{ $booking->owner_notes }}
                    </div>
                    @endif

                    @if($booking->isPending())
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:underline" onclick="return confirm('Cancel this booking request?')">
                            Cancel request
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-slate-200">
            <span class="material-symbols-outlined text-5xl text-slate-300">book_online</span>
            <p class="text-slate-600 font-medium mt-4 mb-2">No bookings yet</p>
            <p class="text-sm text-slate-400">Browse listings and send a booking request to get started.</p>
            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 mt-4 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary/90 transition-colors">
                Browse Listings
            </a>
        </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
    <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection

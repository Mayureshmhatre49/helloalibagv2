@extends('layouts.dashboard')
@section('page-title', 'Bookings')

@section('content')
{{-- Stats Row --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-border-light p-5 text-center">
        <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        <p class="text-xs text-text-secondary mt-1">Pending</p>
    </div>
    <div class="bg-white rounded-2xl border border-border-light p-5 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</p>
        <p class="text-xs text-text-secondary mt-1">Confirmed</p>
    </div>
    <div class="bg-white rounded-2xl border border-border-light p-5 text-center">
        <p class="text-2xl font-bold text-text-main">{{ $stats['total'] }}</p>
        <p class="text-xs text-text-secondary mt-1">Total</p>
    </div>
</div>

{{-- Bookings Table --}}
<div class="bg-white rounded-2xl border border-border-light overflow-hidden">
    <div class="px-6 py-4 border-b border-border-light flex items-center justify-between">
        <h2 class="font-bold text-text-main flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">book_online</span>
            All Booking Requests
        </h2>
    </div>

    @forelse($bookings as $booking)
    <div class="border-b border-border-light last:border-b-0 p-5 hover:bg-background-light/40 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Guest info --}}
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <img src="{{ $booking->user->getAvatarUrl() }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                <div class="min-w-0">
                    <p class="font-semibold text-text-main text-sm truncate">{{ $booking->user->name }}</p>
                    <p class="text-xs text-text-secondary truncate">{{ $booking->listing->title }}</p>
                    @if($booking->check_in)
                        <p class="text-xs text-text-secondary">
                            {{ $booking->check_in->format('d M') }} → {{ $booking->check_out?->format('d M Y') }}
                            · {{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}
                            · {{ $booking->getNights() }} night{{ $booking->getNights() != 1 ? 's' : '' }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Status badge --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $booking->getStatusBadgeClass() }}">
                    {{ $booking->getStatusLabel() }}
                </span>
                <p class="text-xs text-text-secondary">{{ $booking->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Message --}}
        @if($booking->message)
        <div class="mt-3 bg-slate-50 rounded-xl p-3 text-sm text-slate-600 italic">
            "{{ Str::limit($booking->message, 150) }}"
        </div>
        @endif

        {{-- Actions for pending bookings --}}
        @if($booking->isPending())
        <div class="mt-4 flex items-center gap-3" x-data="{ showDecline: false }">
            <form action="{{ route('owner.bookings.confirm', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    Confirm
                </button>
            </form>
            <button @click="showDecline = !showDecline" class="flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                <span class="material-symbols-outlined text-[16px]">cancel</span>
                Decline
            </button>
            <div x-show="showDecline" x-transition class="flex-1">
                <form action="{{ route('owner.bookings.decline', $booking) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="owner_notes" placeholder="Reason (optional)..." class="flex-1 border border-border-light rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400 outline-none">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-red-700">Send</button>
                </form>
            </div>
        </div>
        @elseif($booking->owner_notes)
            <p class="mt-2 text-xs text-text-secondary">Note: {{ $booking->owner_notes }}</p>
        @endif
    </div>
    @empty
    <div class="p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-300">book_online</span>
        <p class="text-text-secondary mt-3">No booking requests yet.</p>
        <p class="text-xs text-text-secondary mt-1">When visitors request a booking through your listing, they'll appear here.</p>
    </div>
    @endforelse
</div>

@if($bookings->hasPages())
<div class="mt-6">{{ $bookings->links() }}</div>
@endif
@endsection

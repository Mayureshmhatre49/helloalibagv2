@extends('layouts.admin')
@section('page-title', 'Inquiry Detail')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Inquiries
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mb-4">
        <div class="flex items-center justify-between mb-4">
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $inquiry->getStatusBadgeClass() }}">{{ $inquiry->getStatusLabel() }}</span>
            <span class="text-xs text-slate-400">{{ $inquiry->created_at->format('M d, Y h:i A') }}</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">From</p>
                <p class="font-semibold text-slate-900">{{ $inquiry->name }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Listing</p>
                <p class="font-semibold text-slate-900">{{ $inquiry->listing->title ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Email</p>
                <p class="text-sm text-primary">{{ $inquiry->email }}</p>
            </div>
            @if($inquiry->phone)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Phone</p>
                <p class="text-sm text-slate-700">{{ $inquiry->phone }}</p>
            </div>
            @endif
            @if($inquiry->check_in)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Check-in / Check-out</p>
                <p class="text-sm text-slate-700">{{ $inquiry->check_in->format('M d, Y') }}{{ $inquiry->check_out ? ' → ' . $inquiry->check_out->format('M d, Y') : '' }}</p>
            </div>
            @endif
            @if($inquiry->guests)
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Guests</p>
                <p class="text-sm text-slate-700">{{ $inquiry->guests }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-400 mb-2">Message</p>
            <div class="text-sm text-slate-700 bg-slate-50 rounded-xl p-4 whitespace-pre-wrap">{{ $inquiry->message }}</div>
        </div>
    </div>

    @if($inquiry->owner_reply)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-4">
            <p class="text-sm font-bold text-green-800 mb-1">Owner Reply</p>
            <p class="text-sm text-green-700 whitespace-pre-wrap">{{ $inquiry->owner_reply }}</p>
            <p class="text-xs text-green-500 mt-2">{{ $inquiry->replied_at?->diffForHumans() }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Delete this inquiry?')">
        @csrf @method('DELETE')
        <button type="submit" class="text-red-500 text-sm font-medium hover:underline">Delete Inquiry</button>
    </form>
</div>
@endsection

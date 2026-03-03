@extends('layouts.dashboard')
@section('page-title', 'Ticket #' . $ticket->id)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('owner.support.index') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Support Center
        </a>
    </div>

    {{-- Ticket Header --}}
    <div class="bg-white rounded-2xl border border-border-light p-6 mb-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold text-text-secondary">TICKET #{{ $ticket->id }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->getStatusBadgeClass() }}">
                    {{ $ticket->getStatusLabel() }}
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->getPriorityBadgeClass() }}">
                    {{ ucfirst($ticket->priority) }} Priority
                </span>
            </div>
            <span class="text-xs text-text-secondary">Created {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</span>
        </div>
        <h2 class="text-lg font-bold text-text-main mb-2">{{ $ticket->subject }}</h2>
        <div class="flex flex-wrap gap-4 text-xs text-text-secondary">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">category</span> {{ ucfirst($ticket->category) }}</span>
            @if($ticket->listing)
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">storefront</span> {{ $ticket->listing->title }}</span>
            @endif
            @if($ticket->resolved_at)
                <span class="flex items-center gap-1 text-green-600"><span class="material-symbols-outlined text-[14px]">check_circle</span> Resolved {{ $ticket->resolved_at->diffForHumans() }}</span>
            @endif
        </div>
    </div>

    {{-- Conversation Thread --}}
    <div class="space-y-3 mb-6">
        @foreach($ticket->replies as $reply)
            @if($reply->is_internal_note) @continue @endif
            <div class="flex items-start gap-3 {{ $reply->is_admin_reply ? '' : 'flex-row-reverse' }}">
                <img src="{{ $reply->user->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0 mt-1">
                <div class="flex-1 max-w-[85%] {{ $reply->is_admin_reply ? '' : 'ml-auto' }}">
                    <div class="rounded-2xl p-4 {{ $reply->is_admin_reply ? 'bg-white border border-border-light' : 'bg-primary/5 border border-primary/10' }}">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-semibold text-text-main">{{ $reply->user->name }}</span>
                            @if($reply->is_admin_reply)
                                <span class="text-[10px] font-bold bg-primary/10 text-primary px-1.5 py-0.5 rounded-full">SUPPORT</span>
                            @endif
                            <span class="text-xs text-text-secondary ml-auto">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-text-main leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reply Box --}}
    @if(!in_array($ticket->status, ['closed']))
        <div class="bg-white rounded-2xl border border-border-light p-5">
            <form method="POST" action="{{ route('owner.support.reply', $ticket) }}">
                @csrf
                <label class="block text-sm font-semibold text-text-main mb-2">Your Reply</label>
                <textarea name="message" rows="4" required
                          class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"
                          placeholder="Type your reply...">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <div class="flex justify-end mt-3">
                    <button type="submit" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-gray-50 rounded-2xl border border-border-light p-5 text-center">
            <span class="material-symbols-outlined text-gray-400 text-3xl mb-2">lock</span>
            <p class="text-sm text-text-secondary">This ticket has been closed. If you need further help, please <a href="{{ route('owner.support.create') }}" class="text-primary hover:underline font-medium">open a new ticket</a>.</p>
        </div>
    @endif
</div>
@endsection

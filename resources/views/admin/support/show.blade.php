@extends('layouts.admin')
@section('page-title', 'Ticket #' . $ticket->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-1 text-sm text-text-secondary hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Thread --}}
        <div class="lg:col-span-2">
            {{-- Ticket Header --}}
            <div class="bg-white rounded-2xl border border-border-light p-6 mb-4">
                <div class="flex items-center gap-2 flex-wrap mb-3">
                    <span class="text-xs font-bold text-text-secondary">TICKET #{{ $ticket->id }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->getStatusBadgeClass() }}">{{ $ticket->getStatusLabel() }}</span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $ticket->getPriorityBadgeClass() }}">{{ ucfirst($ticket->priority) }}</span>
                </div>
                <h2 class="text-lg font-bold text-text-main">{{ $ticket->subject }}</h2>
            </div>

            {{-- Conversation Thread --}}
            <div class="space-y-3 mb-6">
                @foreach($ticket->replies as $reply)
                    @if($reply->is_internal_note)
                        <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 flex items-start gap-2">
                            <span class="material-symbols-outlined text-yellow-600 text-[16px] mt-0.5">sticky_note_2</span>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-yellow-800">Internal Note by {{ $reply->user->name }}</span>
                                    <span class="text-xs text-yellow-600">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-yellow-700">{{ $reply->message }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-3">
                            <img src="{{ $reply->user->getAvatarUrl() }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0 mt-1">
                            <div class="flex-1">
                                <div class="rounded-2xl p-4 {{ $reply->is_admin_reply ? 'bg-primary/5 border border-primary/10' : 'bg-white border border-border-light' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-text-main">{{ $reply->user->name }}</span>
                                        @if($reply->is_admin_reply)
                                            <span class="text-[10px] font-bold bg-primary/10 text-primary px-1.5 py-0.5 rounded-full">ADMIN</span>
                                        @else
                                            <span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-full">USER</span>
                                        @endif
                                        <span class="text-xs text-text-secondary ml-auto">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="text-sm text-text-main leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Reply Box --}}
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <form method="POST" action="{{ route('admin.support.reply', $ticket) }}">
                    @csrf
                    <label class="block text-sm font-semibold text-text-main mb-2">Reply to User</label>
                    <textarea name="message" rows="4" required
                              class="w-full border border-border-light rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all resize-none"
                              placeholder="Type your response...">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <div class="flex items-center justify-between mt-3">
                        <label class="flex items-center gap-2 text-sm text-text-secondary cursor-pointer">
                            <input type="checkbox" name="is_internal_note" value="1" class="rounded border-border-light text-primary focus:ring-primary/20">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">sticky_note_2</span> Internal note (not visible to user)</span>
                        </label>
                        <button type="submit" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="space-y-4">
            {{-- Ticket Details --}}
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <h3 class="text-sm font-bold text-text-main mb-4">Ticket Details</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Requester</span>
                        <div class="flex items-center gap-1.5">
                            <img src="{{ $ticket->user->getAvatarUrl() }}" class="w-5 h-5 rounded-full object-cover">
                            <span class="font-medium text-text-main">{{ $ticket->user->name }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Email</span>
                        <a href="mailto:{{ $ticket->user->email }}" class="text-primary hover:underline text-xs">{{ $ticket->user->email }}</a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Category</span>
                        <span class="font-medium text-text-main">{{ ucfirst($ticket->category) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Created</span>
                        <span class="font-medium text-text-main">{{ $ticket->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($ticket->listing)
                    <div class="pt-2 border-t border-border-light">
                        <span class="text-text-secondary text-xs">Related Listing</span>
                        <p class="font-medium text-text-main mt-0.5">{{ $ticket->listing->title }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Change Status --}}
            <div class="bg-white rounded-2xl border border-border-light p-5">
                <h3 class="text-sm font-bold text-text-main mb-3">Update Status</h3>
                <form method="POST" action="{{ route('admin.support.status', $ticket) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full border border-border-light rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white mb-3">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="w-full bg-charcoal text-white py-2.5 rounded-xl text-sm font-bold hover:bg-charcoal/90 transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

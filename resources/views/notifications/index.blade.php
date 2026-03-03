@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-text-main">Notifications</h1>
        @if($notifications->contains(fn($n) => !$n->read_at))
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="text-sm text-primary font-medium hover:underline">Mark all as read</button>
            </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="space-y-2">
            @foreach($notifications as $notification)
                <a href="{{ route('notifications.read', $notification) }}"
                   class="flex items-start gap-4 p-4 rounded-xl border transition-all {{ $notification->read_at ? 'bg-white border-border-light' : 'bg-primary/5 border-primary/20' }} hover:shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-background-light flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ $notification->getIconClass() }} text-[20px]">{{ $notification->getIcon() }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-text-main">{{ $notification->title }}</p>
                            @if(!$notification->read_at)
                                <span class="w-2 h-2 bg-primary rounded-full flex-shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-sm text-text-secondary line-clamp-2 mt-0.5">{{ $notification->message }}</p>
                        <p class="text-xs text-text-secondary mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">notifications_off</span>
            <p class="text-text-main font-medium mb-1">No notifications yet</p>
            <p class="text-sm text-text-secondary">You'll see updates about your listings, inquiries, and reviews here.</p>
        </div>
    @endif
</div>
@endsection

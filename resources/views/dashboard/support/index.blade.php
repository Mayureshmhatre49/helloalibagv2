@extends('layouts.dashboard')
@section('page-title', 'Support Center')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-text-main">Support Center</h2>
        <p class="text-sm text-text-secondary mt-1">Get help from our team — we typically respond within a few hours.</p>
    </div>
    <a href="{{ route('owner.support.create') }}" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors shadow-sm">
        <span class="material-symbols-outlined text-[18px]">add</span>
        New Ticket
    </a>
</div>

{{-- Status Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach([
        'all' => ['label' => 'All', 'icon' => 'inbox'],
        'open' => ['label' => 'Open', 'icon' => 'radio_button_unchecked'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'pending'],
        'resolved' => ['label' => 'Resolved', 'icon' => 'check_circle'],
    ] as $key => $tab)
        <a href="{{ route('owner.support.index', ['status' => $key]) }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                  {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-text-secondary border-border-light hover:border-primary/30 hover:text-primary' }}">
            <span class="material-symbols-outlined text-[16px]">{{ $tab['icon'] }}</span>
            {{ $tab['label'] }}
            <span class="ml-1 text-xs opacity-75">({{ $counts[$key] ?? 0 }})</span>
        </a>
    @endforeach
</div>

{{-- Tickets List --}}
@if($tickets->count() > 0)
    <div class="space-y-3">
        @foreach($tickets as $ticket)
            <a href="{{ route('owner.support.show', $ticket) }}" class="block bg-white rounded-2xl border border-border-light p-5 hover:shadow-md hover:border-primary/20 transition-all group">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ticket->getStatusBadgeClass() }}">
                                {{ $ticket->getStatusLabel() }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ticket->getPriorityBadgeClass() }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                            <span class="text-xs text-text-secondary">{{ ucfirst($ticket->category) }}</span>
                        </div>
                        <h3 class="font-semibold text-text-main group-hover:text-primary transition-colors truncate">{{ $ticket->subject }}</h3>
                        @if($ticket->listing)
                            <p class="text-xs text-text-secondary mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">storefront</span>
                                {{ $ticket->listing->title }}
                            </p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-text-secondary">{{ $ticket->getTimeAgo() }}</p>
                        <div class="flex items-center gap-1 mt-1.5 text-xs text-text-secondary">
                            <span class="material-symbols-outlined text-[14px]">chat_bubble</span>
                            {{ $ticket->replies_count }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $tickets->appends(['status' => $status])->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <div class="size-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">support_agent</span>
        </div>
        <p class="text-text-main font-semibold mb-1">No tickets{{ $status !== 'all' ? ' with this status' : '' }}</p>
        <p class="text-sm text-text-secondary mb-5">Have a question or need help? Create a support ticket.</p>
        <a href="{{ route('owner.support.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Create Your First Ticket
        </a>
    </div>
@endif
@endsection

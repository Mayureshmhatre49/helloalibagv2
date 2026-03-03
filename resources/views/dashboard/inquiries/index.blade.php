@extends('layouts.dashboard')
@section('page-title', 'Inquiries')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-text-main">Inquiries</h2>
        <p class="text-sm text-text-secondary mt-1">Messages from people interested in your listings.</p>
    </div>
</div>

{{-- Status Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach([
        'all' => ['label' => 'All', 'icon' => 'inbox'],
        'new' => ['label' => 'New', 'icon' => 'mark_email_unread'],
        'read' => ['label' => 'Read', 'icon' => 'drafts'],
        'replied' => ['label' => 'Replied', 'icon' => 'reply'],
    ] as $key => $tab)
        <a href="{{ route('owner.inquiries.index', ['status' => $key]) }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                  {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-text-secondary border-border-light hover:border-primary/30 hover:text-primary' }}">
            <span class="material-symbols-outlined text-[16px]">{{ $tab['icon'] }}</span>
            {{ $tab['label'] }}
            <span class="ml-1 text-xs opacity-75">({{ $counts[$key] ?? 0 }})</span>
        </a>
    @endforeach
</div>

@if($inquiries->count() > 0)
    <div class="space-y-3">
        @foreach($inquiries as $inquiry)
            <a href="{{ route('owner.inquiries.show', $inquiry) }}" class="block bg-white rounded-2xl border border-border-light p-5 hover:shadow-md hover:border-primary/20 transition-all group {{ $inquiry->status === 'new' ? 'border-l-4 border-l-primary' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $inquiry->getStatusBadgeClass() }}">
                                {{ $inquiry->getStatusLabel() }}
                            </span>
                            <span class="text-xs text-text-secondary">{{ $inquiry->listing->title }}</span>
                        </div>
                        <h3 class="font-semibold text-text-main group-hover:text-primary transition-colors">{{ $inquiry->name }}</h3>
                        <p class="text-sm text-text-secondary mt-1 line-clamp-1">{{ $inquiry->message }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-text-secondary">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">mail</span> {{ $inquiry->email }}</span>
                            @if($inquiry->phone)
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">call</span> {{ $inquiry->phone }}</span>
                            @endif
                            @if($inquiry->check_in)
                                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">calendar_today</span> {{ $inquiry->check_in->format('M d') }}{{ $inquiry->check_out ? ' – ' . $inquiry->check_out->format('M d') : '' }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs text-text-secondary flex-shrink-0">{{ $inquiry->created_at->diffForHumans() }}</span>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-6">{{ $inquiries->appends(['status' => $status])->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <div class="size-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl">mail</span>
        </div>
        <p class="text-text-main font-semibold mb-1">No inquiries{{ $status !== 'all' ? ' with this status' : '' }} yet</p>
        <p class="text-sm text-text-secondary">When visitors send inquiries about your listings, they'll appear here.</p>
    </div>
@endif
@endsection

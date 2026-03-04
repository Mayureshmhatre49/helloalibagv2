@extends('layouts.admin')
@section('page-title', 'Support Tickets')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-text-main">Support Tickets</h2>
        <p class="text-sm text-text-secondary mt-1">Manage and respond to user support requests.</p>
    </div>
</div>

{{-- Status Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach([
        'open' => ['label' => 'Open', 'icon' => 'radio_button_unchecked', 'color' => 'blue'],
        'in_progress' => ['label' => 'In Progress', 'icon' => 'pending', 'color' => 'amber'],
        'resolved' => ['label' => 'Resolved', 'icon' => 'check_circle', 'color' => 'green'],
        'closed' => ['label' => 'Closed', 'icon' => 'lock', 'color' => 'gray'],
        'all' => ['label' => 'All', 'icon' => 'inbox', 'color' => 'slate'],
    ] as $key => $tab)
        <a href="{{ route('admin.support.index', ['status' => $key]) }}"
           class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                  {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-text-secondary border-border-light hover:border-primary/30 hover:text-primary' }}">
            <span class="material-symbols-outlined text-[16px]">{{ $tab['icon'] }}</span>
            {{ $tab['label'] }}
            <span class="ml-1 text-xs opacity-75">({{ $counts[$key] ?? 0 }})</span>
        </a>
    @endforeach
</div>

{{-- Tickets Table --}}
@if($tickets->count() > 0)
    <div class="bg-white rounded-2xl border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-background-light border-b border-border-light">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">#</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Subject</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">User</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Priority</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Replies</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Created</th>
                        <th class="text-right px-5 py-3 font-medium text-text-secondary">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @foreach($tickets as $ticket)
                        <tr class="hover:bg-background-light/50 transition-colors">
                            <td class="px-5 py-4 font-mono text-xs text-text-secondary">#{{ $ticket->id }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.support.show', $ticket) }}" class="font-medium text-text-main hover:text-primary transition-colors">{{ Str::limit($ticket->subject, 40) }}</a>
                                <p class="text-xs text-text-secondary mt-0.5">{{ ucfirst($ticket->category) }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <img src="{{ $ticket->user->getAvatarUrl() }}" class="w-7 h-7 rounded-full object-cover">
                                    <span class="text-text-main text-sm">{{ $ticket->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ticket->getPriorityBadgeClass() }}">{{ ucfirst($ticket->priority) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $ticket->getStatusBadgeClass() }}">{{ $ticket->getStatusLabel() }}</span>
                            </td>
                            <td class="px-5 py-4 text-text-secondary">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">chat_bubble</span>
                                    {{ $ticket->replies_count }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-text-secondary">{{ $ticket->created_at->diffForHumans() }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.support.show', $ticket) }}" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:underline">
                                    View <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $tickets->appends(['status' => $status])->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">support_agent</span>
        <p class="text-text-main font-medium mb-1">No tickets{{ $status !== 'all' ? ' with this status' : '' }}</p>
        <p class="text-sm text-text-secondary">Support tickets from listing owners will appear here.</p>
    </div>
@endif
@endsection

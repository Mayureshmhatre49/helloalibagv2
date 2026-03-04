@extends('layouts.admin')
@section('page-title', 'All Inquiries')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Platform Inquiries</h2>
        <p class="text-sm text-slate-500 mt-1">All inquiries sent across all listings.</p>
    </div>
    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="flex items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search inquiries..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none w-56">
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium">Search</button>
    </form>
</div>

{{-- Status Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach(['all' => 'All', 'new' => 'New', 'read' => 'Read', 'replied' => 'Replied'] as $key => $label)
        <a href="{{ route('admin.inquiries.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-xl text-sm font-medium border transition-colors {{ $status === $key ? 'bg-primary text-white border-primary' : 'bg-white text-slate-600 border-slate-200 hover:border-primary/30' }}">
            {{ $label }} <span class="text-xs opacity-75">({{ $counts[$key] }})</span>
        </a>
    @endforeach
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">From</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listing</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Date</th>
                    <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($inquiries as $inq)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-900">{{ $inq->name }}</p>
                            <p class="text-xs text-slate-500">{{ $inq->email }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-slate-700 text-sm line-clamp-1">{{ $inq->listing->title ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $inq->getStatusBadgeClass() }}">{{ $inq->getStatusLabel() }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-500 text-xs">{{ $inq->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.inquiries.show', $inq) }}" class="text-primary text-xs font-bold hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No inquiries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-6">{{ $inquiries->appends(request()->query())->links() }}</div>
@endsection

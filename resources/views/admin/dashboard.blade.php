@extends('layouts.admin')
@section('page-title', 'Overview')

@section('content')
{{-- Metrics --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-primary text-3xl">list_alt</span>
            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg">Total</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalListings }}</p>
        <p class="text-xs text-slate-500 font-medium">Business Listings</p>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-amber-500 text-3xl">pending_actions</span>
            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Action Required</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $pendingListings }}</p>
        <p class="text-xs text-slate-500 font-medium">Pending Approvals</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-rose-500 text-3xl">stars</span>
            <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">Premium</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $premiumListings }}</p>
        <p class="text-xs text-slate-500 font-medium">Featured & Sponsored</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="material-symbols-outlined text-blue-500 text-3xl">group</span>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Growth</span>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ $totalOwners }}</p>
        <p class="text-xs text-slate-500 font-medium">Business Owners</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Recent Activity Feed (Notifications) --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">notifications</span>
                Recent Activity
            </h2>
            <span class="text-xs text-slate-400">Past 48 hours</span>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($activityFeed as $item)
                <div class="px-6 py-4 flex gap-4 hover:bg-slate-50/50 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ $item['color'] }} text-[20px]">{{ $item['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-sm font-bold text-slate-900">{{ $item['title'] }}</p>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $item['time']->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ $item['description'] }}</p>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-sm text-slate-400 italic">No recent activity found</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pending Approvals Quick List --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">fact_check</span>
                Recent Submissions
            </h2>
            <a href="{{ route('admin.listings.index') }}" class="text-xs text-primary font-bold hover:underline">View All</a>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($recentListings as $listing)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0">
                            @if($listing->getPrimaryImageUrl())
                                <img src="{{ $listing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <span class="material-symbols-outlined">image</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 line-clamp-1">{{ $listing->title }}</p>
                            <p class="text-[11px] text-slate-500 font-medium">{{ $listing->category->name }} · {{ $listing->creator->name }}</p>
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'approved' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider {{ $statusColors[$listing->status] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $listing->status }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


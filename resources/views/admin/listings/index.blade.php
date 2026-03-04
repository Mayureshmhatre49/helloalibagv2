@extends('layouts.admin')
@section('page-title', 'Listing Approval Queue')

@section('content')
{{-- Status Tabs --}}
<div class="flex items-center gap-2 mb-6">
    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
        <a href="{{ route('admin.listings.index', ['status' => $key]) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === $key ? 'bg-primary text-white' : 'bg-white text-text-secondary border border-border-light hover:bg-background-light' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

@if($listings->count() > 0)
    <div class="bg-white rounded-2xl border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-background-light border-b border-border-light">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Listing</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Category</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Owner</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Date</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Status</th>
                        <th class="text-right px-5 py-3 font-medium text-text-secondary">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @foreach($listings as $listing)
                        <tr x-data="{ showRejectModal: false }" class="hover:bg-background-light/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        @if($listing->getPrimaryImageUrl())
                                            <img src="{{ $listing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-main">{{ $listing->title }}</p>
                                        @if($listing->area)
                                            <p class="text-xs text-text-secondary">{{ $listing->area->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-text-secondary">{{ $listing->category->name }}</td>
                            <td class="px-5 py-4 text-text-secondary">{{ $listing->creator->name }}</td>
                            <td class="px-5 py-4 text-text-secondary">{{ $listing->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $statusColors = [
                                        'approved' => 'bg-green-50 text-green-700 border-green-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColors[$listing->status] ?? '' }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($listing->status === 'pending')
                                        <form method="POST" action="{{ route('admin.listings.approve', $listing) }}">
                                            @csrf
                                            <button class="flex items-center gap-1 text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors" title="Approve">
                                                <span class="material-symbols-outlined text-[16px]">check</span>
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button" @click="showRejectModal = true" class="flex items-center gap-1 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors" title="Reject">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                            Reject
                                        </button>
                                        
                                        <!-- Reject Modal -->
                                        <div x-show="showRejectModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center text-left" style="display: none;" x-transition.opacity>
                                            <div @click.away="showRejectModal = false" class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">
                                                <h3 class="text-lg font-bold text-slate-900 mb-2">Reject Listing</h3>
                                                <p class="text-sm text-slate-500 mb-4">Please provide a reason for rejecting "{{ $listing->title }}". This will be visible to the owner.</p>
                                                <form method="POST" action="{{ route('admin.listings.reject', $listing) }}">
                                                    @csrf
                                                    <textarea name="rejection_reason" required rows="3" class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 mb-4 text-sm" placeholder="Reason for rejection..."></textarea>
                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="showRejectModal = false" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Cancel</button>
                                                        <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg font-bold shadow-sm transition-colors cursor-pointer">Confirm Reject</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('admin.listings.toggle-featured', $listing) }}">
                                        @csrf @method('PATCH')
                                        <button class="text-xs font-medium {{ $listing->is_featured ? 'text-primary bg-primary/10' : 'text-text-secondary bg-background-light' }} hover:opacity-80 px-2.5 py-1.5 rounded-lg transition-colors" title="Toggle Featured">
                                            <span class="material-symbols-outlined text-[16px]">{{ $listing->is_featured ? 'star' : 'star_border' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $listings->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">approval</span>
        <p class="text-text-main font-medium">No listings to review</p>
        <p class="text-sm text-text-secondary mt-1">All caught up! 🎉</p>
    </div>
@endif
@endsection

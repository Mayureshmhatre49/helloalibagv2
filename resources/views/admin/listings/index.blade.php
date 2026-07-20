@extends('layouts.admin')
@section('page-title', 'Listing Approval Queue')

@section('content')
{{-- Toolbar: Status tabs + Import button --}}

<div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
    <div class="flex items-center gap-2 flex-wrap">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.listings.index', ['status' => $key]) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === $key ? 'bg-primary text-white' : 'bg-white text-text-secondary border border-border-light hover:bg-background-light' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <a href="{{ route('admin.listings.import') }}" class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <span class="material-symbols-outlined text-[18px]">upload_file</span>
        Import CSV
    </a>
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
                                    <div class="relative w-12 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-300">
                                        <span class="material-symbols-outlined text-[18px]">image</span>
                                        @if($listing->getPrimaryImageUrl())
                                            {{-- Sits on top of the placeholder; if the URL is dead it removes
                                                 itself and the icon underneath shows. --}}
                                            <img src="{{ $listing->getPrimaryImageUrl() }}" class="absolute inset-0 w-full h-full object-cover"
                                                 onerror="this.remove()">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-main">{{ $listing->title }}</p>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @if($listing->area)
                                                <p class="text-xs text-text-secondary">{{ $listing->area->name }}</p>
                                            @endif
                                            @if(($listing->category->slug ?? '') === 'real-estate' && $listing->status !== 'approved')
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide" title="Real Estate is paid — collect offline payment before approving">
                                                    <span class="material-symbols-outlined text-[12px]">payments</span> Collect payment
                                                </span>
                                            @endif
                                        </div>
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
                                    <a href="{{ route('listing.show', [$listing->category->slug ?? 'stay', $listing->slug]) }}" target="_blank" rel="noopener"
                                       class="flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-colors" title="View full listing / uploaded data">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        View
                                    </a>
                                    <a href="{{ route('admin.listings.edit', $listing) }}"
                                       class="flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors" title="Edit listing">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                        Edit
                                    </a>
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
                                    <form method="POST" action="{{ route('admin.listings.toggle-verified', $listing) }}"
                                          onsubmit="return {{ $listing->is_verified ? 'confirm(\'Revoke verification?\')' : 'true' }};">
                                        @csrf @method('PATCH')
                                        <button class="text-xs font-medium {{ $listing->is_verified ? 'text-emerald-700 bg-emerald-50' : 'text-text-secondary bg-background-light' }} hover:opacity-80 px-2.5 py-1.5 rounded-lg transition-colors"
                                                title="{{ $listing->is_verified ? 'Revoke verification' : 'Mark as verified' }}">
                                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' {{ $listing->is_verified ? '1' : '0' }}">verified</span>
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

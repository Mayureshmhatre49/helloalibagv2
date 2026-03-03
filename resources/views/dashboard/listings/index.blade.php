@extends('layouts.dashboard')
@section('page-title', 'My Listings')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-text-main">My Listings</h2>
    <a href="{{ route('owner.onboarding.start') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span>
        Add Listing
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
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Views</th>
                        <th class="text-right px-5 py-3 font-medium text-text-secondary">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @foreach($listings as $listing)
                        <tr class="hover:bg-background-light/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        @if($listing->getPrimaryImageUrl())
                                            <img src="{{ $listing->getPrimaryImageUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><span class="material-symbols-outlined text-gray-300 text-sm">image</span></div>
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
                                <td class="px-5 py-4">
                                    @php
                                        $statusColors = [
                                            'approved' => 'bg-green-50 text-green-700 border-green-200',
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                            'draft' => 'bg-gray-50 text-gray-600 border-gray-200',
                                        ];
                                        $statusLabels = [
                                            'approved' => 'Approved',
                                            'pending' => 'Pending Approval',
                                            'rejected' => 'Rejected',
                                            'draft' => 'Draft',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusColors[$listing->status] ?? '' }}">
                                        @if($listing->status === 'pending')
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                        @endif
                                        {{ $statusLabels[$listing->status] ?? ucfirst($listing->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-text-secondary">{{ number_format($listing->views_count) }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('owner.listings.edit', $listing) }}" class="text-text-secondary hover:text-primary p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('owner.listings.destroy', $listing) }}" onsubmit="return confirm('Delete this listing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-text-secondary hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @if($listing->status === 'rejected' && $listing->rejection_reason)
                        <tr class="bg-red-50/50">
                            <td colspan="5" class="px-5 py-3 border-t border-red-100">
                                <div class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-red-500 text-[18px] mt-0.5">error</span>
                                    <div>
                                        <p class="text-sm font-bold text-red-800">Listing Rejected</p>
                                        <p class="text-sm text-red-600">{{ $listing->rejection_reason }}</p>
                                    </div>
                                    <div class="ml-auto">
                                        <a href="{{ route('owner.listings.edit', $listing) }}" class="text-xs font-bold text-red-700 hover:underline">Edit & Resubmit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $listings->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">list</span>
        <p class="text-text-main font-medium mb-1">No listings yet</p>
        <p class="text-sm text-text-secondary mb-4">Create your first listing to get started.</p>
        <a href="{{ route('owner.onboarding.start') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-primary-dark">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Add Listing
        </a>
    </div>
@endif
@endsection

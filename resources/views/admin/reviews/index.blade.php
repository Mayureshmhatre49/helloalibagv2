@extends('layouts.admin')
@section('page-title', 'Review Management')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    {{-- Header & Filters --}}
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user or listing..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary">
            </div>
            
            <div class="flex gap-4">
                <select name="status" onchange="this.form.submit()" 
                        class="pl-4 pr-10 py-2.5 rounded-xl border-slate-200 text-sm focus:border-primary focus:ring-primary bg-white">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 transition-colors text-sm font-bold flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[11px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-6 py-4">Reviewer</th>
                    <th class="px-6 py-4">Listing</th>
                    <th class="px-6 py-4">Rating & Comment</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($reviews as $review)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-100 flex-shrink-0">
                                    <img src="{{ optional($review->user)->getAvatarUrl() }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 leading-none mb-1">{{ optional($review->user)->name ?? 'Deleted User' }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium">{{ optional($review->user)->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($review->listing)
                                <a href="{{ route('listing.show', [$review->listing->category->slug, $review->listing->slug]) }}" target="_blank" class="text-sm font-bold text-primary hover:underline line-clamp-1">
                                    {{ $review->listing->title }}
                                </a>
                                <p class="text-[11px] text-slate-500 font-medium">{{ $review->listing->category->name }}</p>
                            @else
                                <span class="text-sm font-medium text-slate-500">Deleted Listing</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="flex items-center gap-0.5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined {{ $i <= $review->rating ? 'text-amber-400 filled' : 'text-slate-200' }}" style="font-size: 14px;">star</span>
                                @endfor
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-2" title="{{ $review->comment }}">{{ $review->comment }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'approved' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase tracking-wider {{ $statusColors[$review->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $review->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs text-slate-500 font-medium">{{ $review->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($review->status !== 'approved')
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition-colors" title="Approve">
                                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($review->status !== 'rejected')
                                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors" title="Reject">
                                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review completely?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <span class="material-symbols-outlined text-4xl text-slate-200 mb-2">reviews</span>
                                <p class="text-sm text-slate-500 italic">No reviews found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($reviews->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection

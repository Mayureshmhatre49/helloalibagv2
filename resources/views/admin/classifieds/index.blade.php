@extends('layouts.admin')
@section('page-title', 'Marketplace Moderation')

@section('content')
<div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
    <div class="flex items-center gap-2 flex-wrap">
        @foreach(['pending' => 'Pending', 'active' => 'Live', 'sold' => 'Sold', 'expired' => 'Expired', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.classifieds.index', ['status' => $key]) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === $key ? 'bg-primary text-white' : 'bg-white text-text-secondary border border-border-light hover:bg-background-light' }}">
                {{ $label }}
                @if($key !== 'all' && ($counts[$key] ?? 0) > 0)
                    <span class="ml-1 text-[10px] {{ $status === $key ? 'text-white/80' : 'text-primary' }} font-bold">{{ $counts[$key] }}</span>
                @endif
            </a>
        @endforeach
    </div>
    <a href="{{ route('admin.classifieds.create') }}" class="flex items-center gap-1.5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
        <span class="material-symbols-outlined text-[18px]">add_circle</span>
        Add item
    </a>
</div>

@if($classifieds->count() > 0)
    <div class="bg-white rounded-2xl border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-background-light border-b border-border-light">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Item</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Category</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Seller</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Price</th>
                        <th class="text-left px-5 py-3 font-medium text-text-secondary">Status</th>
                        <th class="text-right px-5 py-3 font-medium text-text-secondary">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @foreach($classifieds as $item)
                        @php
                            $statusColors = [
                                'pending'  => 'bg-amber-50 text-amber-700 border-amber-200',
                                'active'   => 'bg-green-50 text-green-700 border-green-200',
                                'sold'     => 'bg-slate-100 text-slate-600 border-slate-200',
                                'expired'  => 'bg-slate-50 text-slate-500 border-slate-200',
                                'rejected' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                        @endphp
                        <tr x-data="{ showReject: false }" class="hover:bg-background-light/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        @if($item->getPrimaryImageUrl())<img src="{{ $item->getPrimaryImageUrl() }}" class="w-full h-full object-cover">@endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-main">{{ $item->title }}</p>
                                        @if($item->area)<p class="text-xs text-text-secondary">{{ $item->area->name }}</p>@endif
                                        @if($item->is_featured)<span class="text-[10px] text-amber-600 font-bold">★ Featured</span>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-text-secondary">{{ $item->category->name }}</td>
                            <td class="px-5 py-4 text-text-secondary">{{ $item->seller->name }}</td>
                            <td class="px-5 py-4 text-text-secondary">{{ $item->price ? '₹' . number_format($item->price) : '—' }}</td>
                            <td class="px-5 py-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusColors[$item->status] ?? '' }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    @if($item->status === 'active' || $item->status === 'sold')
                                        <a href="{{ route('marketplace.show', $item) }}" target="_blank" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-border-light hover:bg-slate-50">View</a>
                                    @endif
                                    <a href="{{ route('admin.classifieds.edit', $item) }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-border-light hover:bg-slate-50">Edit</a>
                                    @if($item->status === 'pending' || $item->status === 'rejected')
                                        @php
                                            $itemApproveConfirm = $item->images->isEmpty()
                                                ? 'Approve "' . addslashes($item->title) . '" even though it has no photos?'
                                                : '';
                                        @endphp
                                        <form method="POST" action="{{ route('admin.classifieds.approve', $item) }}"
                                              @if($itemApproveConfirm) onsubmit="return confirm('{{ $itemApproveConfirm }}')" @endif>
                                            @csrf
                                            <button class="text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">Approve</button>
                                        </form>
                                    @endif
                                    @if($item->status === 'pending')
                                        <button @click="showReject = true" class="text-xs font-bold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">Reject</button>
                                    @endif
                                    @if($item->status === 'active')
                                        <form method="POST" action="{{ route('admin.classifieds.toggleFeatured', $item) }}">
                                            @csrf
                                            <button class="text-xs font-semibold px-3 py-1.5 rounded-lg border {{ $item->is_featured ? 'bg-amber-100 text-amber-700 border-amber-200' : 'border-border-light hover:bg-slate-50' }}">
                                                {{ $item->is_featured ? 'Unfeature' : 'Feature' }}
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.classifieds.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-semibold px-3 py-1.5 rounded-lg text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                </div>

                                {{-- Reject modal --}}
                                <div x-show="showReject" x-cloak @click.outside="showReject = false" class="absolute right-8 mt-2 w-72 bg-white rounded-xl shadow-2xl border border-border-light p-4 z-30 text-left">
                                    <form method="POST" action="{{ route('admin.classifieds.reject', $item) }}">
                                        @csrf
                                        <label class="block text-xs font-bold text-text-main mb-1.5">Reason for rejection</label>
                                        <textarea name="rejection_reason" rows="3" required class="w-full border border-border-light rounded-lg px-3 py-2 text-sm mb-2" placeholder="e.g. photos unclear, prohibited item…"></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showReject = false" class="text-xs px-3 py-1.5 rounded-lg text-text-secondary hover:bg-slate-50">Cancel</button>
                                            <button class="text-xs font-bold px-3 py-1.5 rounded-lg bg-red-600 text-white hover:bg-red-700">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $classifieds->links() }}</div>
@else
    <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">inventory_2</span>
        <p class="text-text-main font-medium">No items in "{{ $status }}".</p>
    </div>
@endif
@endsection

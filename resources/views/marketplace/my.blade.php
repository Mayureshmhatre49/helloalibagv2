@extends('layouts.app')
@section('title', 'My Items — Marketplace')

@section('content')
@php
    $statusBadge = [
        'pending'  => ['Pending review', 'bg-amber-100 text-amber-700'],
        'active'   => ['Live', 'bg-emerald-100 text-emerald-700'],
        'sold'     => ['Sold', 'bg-slate-200 text-slate-700'],
        'expired'  => ['Expired', 'bg-slate-100 text-slate-500'],
        'rejected' => ['Needs changes', 'bg-red-100 text-red-700'],
    ];
@endphp
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-text-main">My Items</h1>
            <p class="text-text-secondary mt-1">Items you've listed for sale.</p>
        </div>
        <a href="{{ route('marketplace.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors shadow">
            <span class="material-symbols-outlined text-[18px]">add_circle</span> Sell an item
        </a>
    </div>

    @if($classifieds->count() > 0)
        <div class="space-y-3">
            @foreach($classifieds as $item)
                @php [$label, $cls] = $statusBadge[$item->status] ?? ['—', 'bg-slate-100 text-slate-600']; @endphp
                <div class="bg-white rounded-2xl border border-border-light p-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <div class="w-20 h-16 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                        @if($item->getPrimaryImageUrl())
                            <img src="{{ $item->getPrimaryImageUrl() }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300"><span class="material-symbols-outlined">image</span></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-slate-900 truncate">{{ $item->title }}</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $cls }}">{{ $label }}</span>
                        </div>
                        <p class="text-sm text-text-secondary">{{ $item->category->name }} · {{ $item->price ? '₹' . number_format($item->price) : 'Ask price' }} · {{ $item->views_count }} views</p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($item->status === 'active')
                            <a href="{{ route('marketplace.show', $item) }}" class="text-xs font-semibold px-3 py-2 rounded-lg border border-border-light hover:bg-slate-50">View</a>
                            <form method="POST" action="{{ route('marketplace.sold', $item) }}" onsubmit="return confirm('Mark this item as sold?')">
                                @csrf
                                <button class="text-xs font-semibold px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100">Mark sold</button>
                            </form>
                        @endif
                        <a href="{{ route('marketplace.edit', $item) }}" class="text-xs font-semibold px-3 py-2 rounded-lg border border-border-light hover:bg-slate-50">Edit</a>
                        <form method="POST" action="{{ route('marketplace.destroy', $item) }}" onsubmit="return confirm('Delete this item permanently?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-semibold px-3 py-2 rounded-lg text-red-600 hover:bg-red-50">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $classifieds->links() }}</div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">sell</span>
            <p class="text-text-main font-medium mb-1">You haven't listed anything yet</p>
            <p class="text-sm text-text-secondary mb-4">List your first item — it's free.</p>
            <a href="{{ route('marketplace.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark">
                <span class="material-symbols-outlined text-[18px]">add_circle</span> Sell an item
            </a>
        </div>
    @endif
</div>
@endsection

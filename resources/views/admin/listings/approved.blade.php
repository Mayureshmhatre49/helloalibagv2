@extends('layouts.admin')
@section('page-title', 'Approved Listings')
@section('page-actions')
    <span class="text-sm font-medium text-slate-500">{{ $totalApproved }} live listings</span>
@endsection

@section('content')

{{-- Search & Filters --}}
<div class="bg-white rounded-2xl border border-border-light p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('admin.listings.approved') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
        {{-- Search --}}
        <div class="flex-1 min-w-0">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Search</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search by listing name..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm">
            </div>
        </div>

        {{-- Category Filter --}}
        <div class="sm:w-48">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Category</label>
            <select name="category" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm py-2.5">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Area Filter --}}
        <div class="sm:w-48">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Location</label>
            <select name="area" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary text-sm py-2.5">
                <option value="">All Areas</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            <button type="submit" class="flex items-center gap-1.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            @if(request()->hasAny(['q', 'category', 'area']))
                <a href="{{ route('admin.listings.approved') }}" class="flex items-center gap-1 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    Clear
                </a>
            @endif
        </div>
    </form>
</div>

@if($listings->count() > 0)
    {{-- Results count --}}
    @if(request()->hasAny(['q', 'category', 'area']))
        <p class="text-sm text-slate-500 mb-3">
            Showing {{ $listings->total() }} result{{ $listings->total() !== 1 ? 's' : '' }}
            @if(request('q')) for "<span class="font-medium text-slate-700">{{ request('q') }}</span>"@endif
        </p>
    @endif

    <div class="bg-white rounded-2xl border border-border-light overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-border-light">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Listing</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Category</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Owner</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Approved</th>
                        <th class="text-center px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Views</th>
                        <th class="text-center px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">SEO Score</th>
                        <th class="text-center px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Listing Score</th>
                        <th class="text-left px-4 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Location</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($listings as $listing)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            {{-- Listing --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[220px]">
                                    <div class="relative w-12 h-10 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-300">
                                        <span class="material-symbols-outlined text-[18px]">image</span>
                                        @if($listing->getPrimaryImageUrl())
                                            <img src="{{ $listing->getPrimaryImageUrl() }}" class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm leading-snug line-clamp-2 max-w-[140px]">{{ $listing->title }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($listing->is_featured)
                                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">star</span> Featured
                                                </span>
                                            @endif
                                            @if($listing->is_verified)
                                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">verified</span> Verified
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $listing->category->name }}
                                </span>
                            </td>

                            {{-- Owner --}}
                            <td class="px-4 py-4 text-slate-600">{{ $listing->creator->name ?? '—' }}</td>

                            {{-- Approved Date --}}
                            <td class="px-4 py-4 text-slate-500 whitespace-nowrap">
                                {{ $listing->approved_at ? $listing->approved_at->format('M d, Y') : $listing->created_at->format('M d, Y') }}
                            </td>

                            {{-- Views --}}
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-[14px] text-slate-400">visibility</span>
                                    {{ number_format($listing->views_count ?? 0) }}
                                </span>
                            </td>

                            {{-- SEO Score --}}
                            <td class="px-4 py-4 text-center">
                                @php
                                    $seoScore = $listing->getSeoScore();
                                    $seoColor = $listing->getSeoColor();
                                    $seoLabel = $listing->getSeoLabel();
                                @endphp
                                <div class="flex flex-col items-center gap-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold {{ $seoColor }}">
                                        {{ $seoScore }}%
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $seoLabel }}</span>
                                </div>
                            </td>

                            {{-- Listing Score --}}
                            <td class="px-4 py-4 text-center">
                                @php
                                    $qualityScore = $listing->getQualityScore();
                                    $qualityColor = $listing->getQualityColor();
                                    $qualityLabel = $listing->getQualityLabel();
                                @endphp
                                <div class="flex flex-col items-center gap-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold {{ $qualityColor }}">
                                        {{ $qualityScore }}%
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $qualityLabel }}</span>
                                </div>
                            </td>

                            {{-- Location --}}
                            <td class="px-4 py-4">
                                @if($listing->area)
                                    <span class="inline-flex items-center gap-1 text-xs text-slate-600">
                                        <span class="material-symbols-outlined text-[14px] text-slate-400">location_on</span>
                                        {{ $listing->area->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('listing.show', [$listing->category->slug ?? 'stay', $listing->slug]) }}" target="_blank" rel="noopener"
                                       class="flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-colors" title="View listing">
                                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                        View
                                    </a>
                                    <a href="{{ route('admin.listings.edit', $listing) }}"
                                       class="flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors" title="Edit listing">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                        Edit
                                    </a>
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
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">inventory_2</span>
        <p class="text-text-main font-medium">No approved listings found</p>
        <p class="text-sm text-text-secondary mt-1">
            @if(request()->hasAny(['q', 'category', 'area']))
                Try adjusting your filters or <a href="{{ route('admin.listings.approved') }}" class="text-primary hover:underline font-medium">clear all filters</a>.
            @else
                Approve pending listings from the <a href="{{ route('admin.listings.index', ['status' => 'pending']) }}" class="text-primary hover:underline font-medium">Approval Queue</a>.
            @endif
        </p>
    </div>
@endif
@endsection

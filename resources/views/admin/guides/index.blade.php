@extends('layouts.admin')
@section('page-title', 'Editorial Guides')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Editorial Guides</h2>
        <p class="text-sm text-text-secondary mt-1">Pillar content that cross-links to curated listings.</p>
    </div>
    <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span> Write Guide
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Guide</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listings</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Stats</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($guides as $g)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if ($g->heroImageUrl())
                                <img src="{{ $g->heroImageUrl() }}" class="w-14 h-14 rounded-lg bg-slate-100 object-cover" alt="Hero" loading="lazy">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                                    <span class="material-symbols-outlined text-[22px]">menu_book</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-slate-900 leading-tight">{{ $g->title }}</h3>
                                <div class="text-xs text-text-secondary mt-1 flex items-center gap-2">
                                    <span>By {{ $g->author?->name ?? 'Unknown' }}</span>
                                    <span class="text-slate-300">·</span>
                                    <span>{{ $g->reading_time }} min read</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-700 font-medium">
                        {{ $g->listings_count }} {{ Str::plural('listing', $g->listings_count) }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-xs text-text-secondary space-y-1">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">visibility</span> {{ number_format($g->views_count) }} views
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                {{ $g->published_at?->format('M j, Y') ?? 'Not published' }}
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex flex-col gap-1.5 items-start">
                            @if ($g->is_published)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                </span>
                            @endif
                            @if ($g->is_featured)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">
                                    <span class="material-symbols-outlined text-[10px]" style="font-variation-settings:'FILL' 1">star</span> Featured
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <a href="{{ route('guides.show', $g) }}" target="_blank"
                           class="inline-flex items-center gap-1 text-text-secondary hover:text-primary text-xs font-bold transition-colors px-2 py-1">
                            <span class="material-symbols-outlined text-[14px]">open_in_new</span> View
                        </a>
                        <a href="{{ route('admin.guides.edit', $g) }}"
                           class="inline-flex items-center gap-1 text-primary hover:text-primary-dark text-xs font-bold transition-colors px-2 py-1">
                            <span class="material-symbols-outlined text-[14px]">edit</span> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.guides.destroy', $g) }}" class="inline"
                              onsubmit="return confirm('Delete this guide? Listings remain intact.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 text-xs font-bold transition-colors px-2 py-1">
                                <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-text-secondary">
                        <span class="material-symbols-outlined text-slate-300 text-[40px] block mb-2">menu_book</span>
                        <p class="text-sm font-medium">No guides yet.</p>
                        <a href="{{ route('admin.guides.create') }}" class="inline-flex items-center gap-1 mt-3 text-primary font-bold text-sm hover:underline">
                            Write your first guide <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $guides->links() }}
</div>
@endsection

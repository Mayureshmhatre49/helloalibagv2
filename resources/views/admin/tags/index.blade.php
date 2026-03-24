@extends('layouts.admin')
@section('page-title', 'Listing Tags')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-900">Listing Tags</h2>
    <a href="{{ route('admin.tags.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span> Add Tag
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Slug</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Icon</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Order</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listings</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($tags as $tag)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $tag->name }}</td>
                    <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ $tag->slug }}</td>
                    <td class="px-5 py-4 text-slate-600">
                        @if($tag->icon)
                            <span class="material-symbols-outlined text-[18px] align-middle">{{ $tag->icon }}</span>
                            <span class="text-xs text-slate-400 ml-1">{{ $tag->icon }}</span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-slate-600">{{ $tag->sort_order }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $tag->listings_count }}</td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.tags.edit', $tag) }}" class="p-1.5 hover:bg-primary/10 rounded-lg text-slate-500 hover:text-primary">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Delete this tag?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 hover:bg-red-50 rounded-lg text-slate-500 hover:text-red-600">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-500">No tags found. <a href="{{ route('admin.tags.create') }}" class="text-primary hover:underline">Create one.</a></td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

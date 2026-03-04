@extends('layouts.admin')
@section('page-title', 'Categories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-900">Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span> Add Category
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Order</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Slug</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listings</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($categories as $cat)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4 text-slate-500 font-mono text-xs">{{ $cat->sort_order }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            @if($cat->icon)<span class="material-symbols-outlined text-primary text-[18px]">{{ $cat->icon }}</span>@endif
                            <span class="font-semibold text-slate-900">{{ $cat->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ $cat->slug }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $cat->listings_count }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cat->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="p-1.5 hover:bg-primary/10 rounded-lg text-slate-500 hover:text-primary"><span class="material-symbols-outlined text-[18px]">edit</span></a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 hover:bg-red-50 rounded-lg text-slate-500 hover:text-red-600"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

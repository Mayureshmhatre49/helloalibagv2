@extends('layouts.admin')
@section('page-title', 'Areas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-900">Areas</h2>
    <a href="{{ route('admin.areas.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span> Add Area
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Name</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Slug</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listings</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($areas as $area)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $area->name }}</td>
                    <td class="px-5 py-4 text-slate-500 text-xs font-mono">{{ $area->slug }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $area->listings_count }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $area->is_active ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">{{ $area->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.areas.edit', $area) }}" class="p-1.5 hover:bg-primary/10 rounded-lg text-slate-500 hover:text-primary"><span class="material-symbols-outlined text-[18px]">edit</span></a>
                            <form method="POST" action="{{ route('admin.areas.destroy', $area) }}" onsubmit="return confirm('Delete this area?')">
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

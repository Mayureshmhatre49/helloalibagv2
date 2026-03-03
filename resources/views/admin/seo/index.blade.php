@extends('layouts.admin')
@section('page-title', 'SEO Manager')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-900">SEO Manager</h2>
        <p class="text-sm text-slate-500 mt-1">Edit meta tags for approved listings.</p>
    </div>
    <form action="{{ route('admin.seo.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search listings..." class="border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none w-56">
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium">Search</button>
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Listing</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Meta Title</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">SEO Status</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($listings as $listing)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4 font-medium text-slate-900">{{ Str::limit($listing->title, 40) }}</td>
                    <td class="px-5 py-4 text-xs text-slate-500">{{ Str::limit($listing->seoMeta->meta_title ?? '—', 50) }}</td>
                    <td class="px-5 py-4">
                        @if($listing->seoMeta)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">Optimized</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Not Set</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.seo.edit', $listing) }}" class="text-primary text-xs font-bold hover:underline">Edit SEO</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-slate-400">No listings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $listings->appends(request()->query())->links() }}</div>
@endsection

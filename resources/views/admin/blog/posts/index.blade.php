@extends('layouts.admin')
@section('page-title', 'Blog Posts')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-slate-900">Blog Posts</h2>
    <a href="{{ route('admin.blog.posts.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">add</span> Write Post
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Post</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Category</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Stats</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($posts as $post)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-12 h-12 rounded bg-slate-100 object-cover" alt="Cover">
                            @else
                                <div class="w-12 h-12 rounded bg-slate-100 flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[20px]">image</span>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $post->title }}</h3>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span>By {{ $post->author?->name ?? 'Unknown' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Unpublished' }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-600">
                        {{ $post->category?->name ?? 'Uncategorized' }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-xs text-slate-500 space-y-1">
                            <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">visibility</span> {{ number_format($post->views_count) }} views</div>
                            <div class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> {{ $post->reading_time }} min read</div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex flex-col items-start gap-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $post->status === 'published' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                            @if($post->is_featured)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Featured</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.blog.posts.edit', $post) }}" class="p-1.5 hover:bg-primary/10 rounded-lg text-slate-500 hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></a>
                            <form method="POST" action="{{ route('admin.blog.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 hover:bg-red-50 rounded-lg text-slate-500 hover:text-red-600" title="Delete"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-slate-500">No blog posts found. <a href="{{ route('admin.blog.posts.create') }}" class="text-primary hover:underline">Write your first post</a>.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-100">
        {{ $posts->links() }}
    </div>
</div>
@endsection

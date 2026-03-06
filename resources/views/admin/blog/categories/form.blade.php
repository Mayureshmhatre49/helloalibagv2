@extends('layouts.admin')
@section('page-title', $category ? 'Edit Blog Category' : 'New Blog Category')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.blog.categories.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-4">{{ $category ? 'Edit' : 'New' }} Blog Category</h2>
        <form method="POST" action="{{ $category ? route('admin.blog.categories.update', $category) : route('admin.blog.categories.store') }}" class="space-y-4">
            @csrf
            @if($category) @method('PUT') @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono">
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">{{ $category ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>

<script>
    // Auto-generate slug from name
    document.querySelector('input[name="name"]').addEventListener('input', function(e) {
        if (!'{{ $category ? 1 : 0 }}') {
            let slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            document.querySelector('input[name="slug"]').value = slug;
        }
    });
</script>
@endsection

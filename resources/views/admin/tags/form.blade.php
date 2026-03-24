@extends('layouts.admin')
@section('page-title', $tag ? 'Edit Tag' : 'New Tag')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Tags
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-4">{{ $tag ? 'Edit' : 'New' }} Listing Tag</h2>

        <form method="POST" action="{{ $tag ? route('admin.tags.update', $tag) : route('admin.tags.store') }}" class="space-y-4">
            @csrf
            @if($tag) @method('PUT') @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="tag-name" value="{{ old('name', $tag->name ?? '') }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Slug <span class="text-red-500">*</span></label>
                <input type="text" name="slug" id="tag-slug" value="{{ old('slug', $tag->slug ?? '') }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono">
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Icon <span class="text-slate-400 font-normal">(Material Symbol name, e.g. <code class="font-mono text-xs">family_restroom</code>)</span></label>
                <div class="flex items-center gap-3">
                    <input type="text" name="icon" id="tag-icon" value="{{ old('icon', $tag->icon ?? '') }}" placeholder="e.g. beach_access"
                           class="flex-1 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono">
                    <div id="icon-preview" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <span class="material-symbols-outlined text-[20px]" id="icon-preview-sym">{{ old('icon', $tag->icon ?? 'label') }}</span>
                    </div>
                </div>
                @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $tag->sort_order ?? 0) }}" min="0"
                       class="w-32 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <p class="text-xs text-slate-400 mt-1">Lower numbers appear first.</p>
                @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">
                {{ $tag ? 'Update Tag' : 'Create Tag' }}
            </button>
        </form>
    </div>
</div>

<script>
    const nameInput = document.getElementById('tag-name');
    const slugInput = document.getElementById('tag-slug');
    const iconInput = document.getElementById('tag-icon');
    const iconPreviewSym = document.getElementById('icon-preview-sym');
    const isEdit = {{ $tag ? 'true' : 'false' }};

    nameInput.addEventListener('input', function () {
        if (!isEdit) {
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        }
    });

    iconInput.addEventListener('input', function () {
        iconPreviewSym.textContent = this.value || 'label';
    });
</script>
@endsection

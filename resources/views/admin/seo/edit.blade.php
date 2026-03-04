@extends('layouts.admin')
@section('page-title', 'Edit SEO — ' . $listing->title)

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.seo.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-1">{{ $listing->title }}</h2>
        <p class="text-xs text-slate-500 mb-6">{{ url($listing->category->slug . '/' . $listing->slug) }}</p>

        <form method="POST" action="{{ route('admin.seo.update', $listing) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Meta Title <span class="text-xs text-slate-400 font-normal">(max 70 chars)</span></label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $listing->seoMeta->meta_title ?? '') }}" maxlength="70" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="{{ $listing->title }}">
                @error('meta_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Meta Description <span class="text-xs text-slate-400 font-normal">(max 160 chars)</span></label>
                <textarea name="meta_description" rows="3" maxlength="160" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder="Brief description for search engines...">{{ old('meta_description', $listing->seoMeta->meta_description ?? '') }}</textarea>
                @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">OG Title</label>
                <input type="text" name="og_title" value="{{ old('og_title', $listing->seoMeta->og_title ?? '') }}" maxlength="100" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Open Graph title for social sharing">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">OG Description</label>
                <textarea name="og_description" rows="2" maxlength="200" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder="Description for social sharing">{{ old('og_description', $listing->seoMeta->og_description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Keywords</label>
                <input type="text" name="keywords" value="{{ old('keywords', $listing->seoMeta->keywords ?? '') }}" maxlength="255" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="comma, separated, keywords">
            </div>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">Save SEO Settings</button>
        </form>
    </div>
</div>
@endsection

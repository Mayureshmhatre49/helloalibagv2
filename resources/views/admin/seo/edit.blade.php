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

            <p class="text-xs text-slate-500 bg-slate-50 border border-slate-100 rounded-lg px-3 py-2 -mt-1">Leave any field blank to use the auto-generated default shown as the placeholder.</p>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Meta Title <span class="text-xs text-slate-500 font-normal">(max 70 chars — becomes the &lt;title&gt; and social title)</span></label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $listing->seoMeta->meta_title ?? '') }}" maxlength="70" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="{{ $listing->title }} — {{ $listing->category->name }} in Alibaug">
                @error('meta_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Meta Description <span class="text-xs text-slate-500 font-normal">(max 160 chars)</span></label>
                <textarea name="meta_description" rows="3" maxlength="160" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none" placeholder="{{ Str::limit(strip_tags($listing->description ?? ''), 155) }}">{{ old('meta_description', $listing->seoMeta->meta_description ?? '') }}</textarea>
                @error('meta_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Social Image URL <span class="text-xs text-slate-500 font-normal">(og:image — 1200×630 recommended)</span></label>
                <input type="url" name="og_image" value="{{ old('og_image', $listing->seoMeta->og_image ?? '') }}" maxlength="500" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Defaults to the listing's cover photo">
                @error('og_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Canonical URL <span class="text-xs text-slate-500 font-normal">(optional — only if this content lives elsewhere)</span></label>
                <input type="url" name="canonical_url" value="{{ old('canonical_url', $listing->seoMeta->canonical_url ?? '') }}" maxlength="500" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="{{ url($listing->category->slug . '/' . $listing->slug) }}">
                @error('canonical_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1.5">Keywords <span class="text-xs text-slate-500 font-normal">(comma-separated)</span></label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $listing->seoMeta->meta_keywords ?? '') }}" maxlength="255" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="villa in alibaug, sea view stay, ...">
            </div>

            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">Save SEO Settings</button>
        </form>
    </div>
</div>
@endsection

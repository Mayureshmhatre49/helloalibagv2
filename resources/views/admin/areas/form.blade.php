@extends('layouts.admin')
@section('page-title', $area ? 'Edit Area' : 'New Area')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-primary mb-6">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-4">{{ $area ? 'Edit' : 'New' }} Area</h2>
        <form method="POST" action="{{ $area ? route('admin.areas.update', $area) : route('admin.areas.store') }}" class="space-y-4">
            @csrf
            @if($area) @method('PUT') @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $area->name ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $area->slug ?? '') }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none font-mono">
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none">{{ old('description', $area->description ?? '') }}</textarea>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $area->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary">
                <span class="text-sm font-medium text-slate-700">Active</span>
            </label>
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-primary-dark transition-colors">{{ $area ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
@endsection

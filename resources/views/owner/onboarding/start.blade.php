@extends('layouts.app')

@section('title', 'Start Listing')

@section('content')
<div class="min-h-[80vh] flex flex-col pt-12 pb-24 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 w-full">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-serif font-bold text-slate-900 mb-4">What do you want to list?</h1>
            <p class="text-slate-500 text-lg">Select a category to begin your guided setup.</p>
        </div>

        <form action="{{ route('owner.onboarding.start') }}" method="POST" x-data="{ selected: '' }">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($categories as $category)
                <label 
                    class="relative flex flex-col items-center justify-center p-8 bg-white rounded-3xl border-2 transition-all cursor-pointer hover:-translate-y-1 hover:shadow-xl"
                    :class="selected == '{{ $category->id }}' ? 'border-primary bg-primary/5 shadow-lg shadow-primary/20' : 'border-slate-100 hover:border-primary/30'"
                >
                    <input type="radio" name="category_id" value="{{ $category->id }}" x-model="selected" class="hidden" required>
                    <div class="size-16 rounded-2xl flex items-center justify-center mb-5 transition-colors"
                         :class="selected == '{{ $category->id }}' ? 'bg-primary text-white' : 'bg-slate-50 text-primary'">
                        <span class="material-symbols-outlined text-3xl">{{ $category->icon ?? 'storefront' }}</span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-xl mb-2">{{ $category->name }}</h3>
                    <p class="text-sm text-slate-500 text-center">{{ $category->description ?? 'List your ' . strtolower($category->name) . ' business' }}</p>
                    
                    <div class="absolute top-4 right-4 size-6 rounded-full border-2 flex items-center justify-center transition-colors"
                         :class="selected == '{{ $category->id }}' ? 'border-primary bg-primary' : 'border-slate-200'">
                        <span class="material-symbols-outlined text-white text-[14px]" x-show="selected == '{{ $category->id }}'">check</span>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="flex justify-center border-t border-slate-200 pt-8">
                <button type="submit" :disabled="!selected" class="bg-primary hover:bg-primary/90 text-white px-10 py-4 rounded-full font-bold text-lg transition-all shadow-xl shadow-primary/30 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed flex items-center gap-2">
                    Continue <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </button>
            </div>
            
            @error('category_id')
                <p class="text-red-500 text-center mt-4 font-medium">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>
@endsection

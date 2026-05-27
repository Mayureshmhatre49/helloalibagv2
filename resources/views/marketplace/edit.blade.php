@extends('layouts.app')
@section('title', 'Edit — ' . $classified->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('marketplace.my') }}" class="text-sm text-primary font-medium inline-flex items-center gap-1 mb-3 hover:underline">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> My items
        </a>
        <h1 class="text-3xl font-bold text-text-main">Edit item</h1>
        @if($classified->status === 'rejected' && $classified->rejection_reason)
            <div class="mt-3 bg-red-50 border border-red-100 text-red-700 text-sm rounded-xl px-4 py-3">
                <strong>Needs changes:</strong> {{ $classified->rejection_reason }}
            </div>
        @endif
    </div>

    <form method="POST" action="{{ route('marketplace.update', $classified) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-border-light p-6">
        @csrf @method('PUT')
        @include('marketplace._form', ['classified' => $classified])

        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-border-light">
            <a href="{{ route('marketplace.my') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-text-secondary hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">save</span> Save changes
            </button>
        </div>
    </form>
</div>
@endsection

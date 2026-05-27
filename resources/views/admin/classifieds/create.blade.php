@extends('layouts.admin')
@section('page-title', 'Add Marketplace Item')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.classifieds.index') }}" class="text-sm text-primary font-medium inline-flex items-center gap-1 mb-4 hover:underline">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to marketplace
    </a>

    <div class="bg-amber-50 border border-amber-100 text-amber-800 text-sm rounded-xl px-4 py-3 mb-5">
        Items you add here go <strong>live immediately</strong> (no approval needed). Set the contact number to the actual seller's so buyers reach the right person.
    </div>

    <form method="POST" action="{{ route('admin.classifieds.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-border-light p-6">
        @csrf
        @include('marketplace._form')

        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-border-light">
            <a href="{{ route('admin.classifieds.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-text-secondary hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">add_circle</span> Add item
            </button>
        </div>
    </form>
</div>
@endsection

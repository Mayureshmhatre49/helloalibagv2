@extends('layouts.admin')
@section('page-title', 'Edit Marketplace Item')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.classifieds.index', ['status' => $classified->status]) }}" class="text-sm text-primary font-medium inline-flex items-center gap-1 mb-4 hover:underline">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to marketplace
    </a>

    <form method="POST" action="{{ route('admin.classifieds.update', $classified) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-border-light p-6">
        @csrf @method('PUT')
        @include('marketplace._form', ['classified' => $classified])

        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-border-light">
            <a href="{{ route('admin.classifieds.index', ['status' => $classified->status]) }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-text-secondary hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-primary-dark transition-colors">
                <span class="material-symbols-outlined text-[18px]">save</span> Save changes
            </button>
        </div>
    </form>
</div>
@endsection

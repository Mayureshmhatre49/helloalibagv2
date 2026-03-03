@extends('layouts.app')
@section('title', 'Search Results')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Search Header --}}
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="max-w-2xl">
            <div class="flex bg-white rounded-xl border border-border-light overflow-hidden shadow-sm">
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Search listings..." class="w-full pl-12 pr-4 py-3.5 text-text-main border-0 focus:ring-0 text-sm">
                </div>
                <button type="submit" class="bg-primary text-white px-6 font-medium text-sm hover:bg-primary-dark transition-colors">Search</button>
            </div>
        </form>
    </div>

    @if($query)
        <p class="text-sm text-text-secondary mb-6">
            @if($results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                {{ $results->total() }} results for "<strong class="text-text-main">{{ $query }}</strong>"
            @else
                Showing results for "<strong class="text-text-main">{{ $query }}</strong>"
            @endif
        </p>

        @if($results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $results->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($results as $listing)
                    @include('components.listing-card', ['listing' => $listing])
                @endforeach
            </div>
            <div class="mt-8">{{ $results->links() }}</div>
        @elseif(($results instanceof \Illuminate\Support\Collection && $results->isEmpty()) || ($results instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $results->isEmpty()))
            <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
                <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">search_off</span>
                <p class="text-text-main font-medium mb-1">No results found</p>
                <p class="text-sm text-text-secondary">Try different keywords or browse categories.</p>
            </div>
        @endif
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-border-light">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">search</span>
            <p class="text-text-main font-medium mb-1">Search Hello Alibaug</p>
            <p class="text-sm text-text-secondary">Find villas, restaurants, events, and more.</p>
        </div>
    @endif
</div>
@endsection

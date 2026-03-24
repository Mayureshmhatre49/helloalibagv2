@if($relatedListings->count() > 0)
    <section class="mt-12 pt-8 border-t border-slate-200">
        <div class="flex items-baseline justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Similar in {{ $listing->category->name }}</h2>
            <a href="{{ route('category.show', $listing->category) }}"
               class="text-sm font-bold text-primary hover:underline flex items-center gap-0.5">
                View all <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($relatedListings as $related)
                @include('components.listing-card', ['listing' => $related])
            @endforeach
        </div>
    </section>
@endif

@if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
    <section class="mt-12 mb-8 pt-8 border-t border-slate-200">
        <h2 class="text-2xl font-bold text-slate-900 mb-6">Recently Viewed</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($recentlyViewed as $recentView)
                @include('components.listing-card', ['listing' => $recentView])
            @endforeach
        </div>
    </section>
@endif

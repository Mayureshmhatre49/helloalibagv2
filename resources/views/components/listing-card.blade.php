{{-- Listing Card (Stitch UI Style) --}}
<a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" class="group bg-white rounded-xl overflow-hidden border border-slate-100 hover:shadow-xl transition-all duration-300 block">
    <div class="relative aspect-[4/3] overflow-hidden">
        @if($listing->getPrimaryImageUrl())
            <img src="{{ $listing->getPrimaryImageUrl() }}" alt="{{ $listing->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                <span class="material-symbols-outlined text-4xl text-slate-300">image</span>
            </div>
        @endif
        {{-- Rating Badge --}}
        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2 py-1 rounded-md text-xs font-bold shadow-sm">
            ★ {{ $listing->getAverageRating() ?: '—' }}
        </div>
        {{-- Feature Badge --}}
        @if($listing->is_featured)
            <div class="absolute top-3 left-3 bg-primary text-white px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                Featured
            </div>
        @elseif($listing->is_premium)
            <div class="absolute top-3 left-3 bg-amber-500 text-white px-2 py-1 rounded-md text-xs font-bold shadow-sm">
                Premium
            </div>
        @endif
    </div>
    <div class="p-4">
        <div class="flex justify-between items-start mb-1">
            <h3 class="font-bold text-lg text-slate-900 truncate">{{ $listing->title }}</h3>
            @auth
                @php $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())->where('listing_id', $listing->id)->exists(); @endphp
                <form method="POST" action="{{ route('wishlist.toggle', $listing) }}" onclick="event.stopPropagation(); event.preventDefault(); this.submit();">
                    @csrf
                    <button type="submit" class="flex-shrink-0 ml-2" title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <span class="material-symbols-outlined {{ $isWishlisted ? 'filled text-red-500' : 'text-slate-400 hover:text-red-500' }} transition-colors">favorite</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex-shrink-0 ml-2" onclick="event.stopPropagation();" title="Login to save">
                    <span class="material-symbols-outlined text-slate-400 hover:text-red-500 transition-colors">favorite</span>
                </a>
            @endauth
        </div>
        <p class="text-sm text-slate-500 mb-3">{{ $listing->area?->name ? $listing->area->name . ', Alibaug' : $listing->category->name }}</p>
        <div class="flex items-baseline gap-1">
            @if($listing->price)
                <span class="font-bold text-slate-900 text-lg">₹{{ number_format($listing->price) }}</span>
                <span class="text-slate-500 text-sm font-medium">/ night</span>
            @else
                <span class="text-sm text-slate-500 font-medium">Contact for price</span>
            @endif
        </div>
    </div>
</a>

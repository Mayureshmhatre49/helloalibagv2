{{-- Listing Card (Stitch UI Style - Static) --}}
<div class="group bg-white rounded-xl overflow-hidden border border-slate-100 hover:shadow-xl transition-all duration-300 flex flex-col h-full relative">
    
    {{-- Main Clickable Area spanning the card --}}
    <a href="{{ route('listing.show', [$listing->category->slug, $listing->slug]) }}" class="absolute inset-0 z-10" aria-label="View {{ $listing->title }}"></a>

    <div class="relative aspect-[4/3] w-full overflow-hidden flex-shrink-0 bg-slate-100">
        @if($listing->getPrimaryImageUrl())
            <img src="{{ $listing->getPrimaryImageUrl() }}" alt="{{ $listing->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-slate-300">image</span>
            </div>
        @endif
        
        {{-- Badges Container (Top) --}}
        <div class="absolute top-3 inset-x-3 flex justify-between items-start z-20 pointer-events-none">
            {{-- Feature Badge --}}
            <div>
                @if($listing->is_featured)
                    <div class="bg-primary text-white px-2 py-1 rounded-md text-[10px] sm:text-xs font-bold shadow-sm backdrop-blur-md bg-opacity-90">
                        Featured
                    </div>
                @elseif($listing->is_premium)
                    <div class="bg-amber-500 text-white px-2 py-1 rounded-md text-[10px] sm:text-xs font-bold shadow-sm backdrop-blur-md bg-opacity-90">
                        Premium
                    </div>
                @endif
            </div>

            {{-- Rating Badge --}}
            <div class="bg-white/90 backdrop-blur px-2 py-1 rounded-md text-[10px] sm:text-xs font-bold shadow-sm text-slate-800 flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[14px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
                {{ $listing->getAverageRating() ?: 'New' }}
            </div>
        </div>
    </div>

    <div class="p-3 sm:p-4 flex flex-col flex-grow relative z-20 pointer-events-none">
        <div class="flex justify-between items-start mb-1">
            <h3 class="font-bold text-lg text-slate-900 truncate">{{ $listing->title }}</h3>
            
            <div class="pointer-events-auto">
                @auth
                    @php $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())->where('listing_id', $listing->id)->exists(); @endphp
                    <form method="POST" action="{{ route('wishlist.toggle', $listing) }}">
                        @csrf
                        <button type="submit" class="flex-shrink-0 ml-2" title="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                            <span class="material-symbols-outlined {{ $isWishlisted ? 'filled text-red-500' : 'text-slate-400 hover:text-red-500' }} transition-colors">favorite</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex-shrink-0 ml-2" title="Login to save">
                        <span class="material-symbols-outlined text-slate-400 hover:text-red-500 transition-colors">favorite</span>
                    </a>
                @endauth
            </div>
        </div>
        <p class="text-sm text-slate-500 mb-3">{{ $listing->area?->name ? $listing->area->name . ', Alibaug' : $listing->category->name }}</p>
        <div class="mt-auto pt-3 flex items-baseline gap-1">
            @if($listing->price)
                <span class="font-bold text-slate-900 text-base sm:text-lg">₹{{ number_format($listing->price) }}</span>
                <span class="text-slate-500 text-xs sm:text-sm font-medium">/ night</span>
            @else
                <span class="text-xs sm:text-sm text-slate-500 font-medium">Contact for info</span>
            @endif
        </div>
    </div>
</div>

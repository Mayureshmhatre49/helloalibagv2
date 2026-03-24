{{-- Mobile sticky bottom bar --}}
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 px-4 py-3 z-40 shadow-[0_-4px_24px_rgba(0,0,0,0.08)]">
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            @if($listing->price)
                <div class="flex items-baseline gap-1">
                    <span class="text-lg font-bold text-slate-900">₹{{ number_format($listing->price) }}</span>
                    @if($priceLabel)
                        <span class="text-xs text-slate-400">{{ $priceLabel }}</span>
                    @endif
                </div>
            @else
                <span class="text-sm font-semibold text-slate-700">{{ $mobileBarLabel ?? 'Contact for pricing' }}</span>
            @endif
            @if($avgRating > 0)
                <div class="flex items-center gap-0.5 mt-0.5">
                    <span class="material-symbols-outlined text-amber-400 text-[12px]" style="font-variation-settings:'FILL' 1">star</span>
                    <span class="text-xs font-bold text-slate-700">{{ $avgRating }}</span>
                    <span class="text-xs text-slate-400">({{ $reviewCount }})</span>
                </div>
            @endif
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            @if($listing->phone)
                <a href="tel:{{ $listing->phone }}"
                   class="flex items-center justify-center w-11 h-11 rounded-xl bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">call</span>
                </a>
            @endif
            @if($listing->whatsapp)
                <a href="https://wa.me/91{{ $listing->whatsapp }}?text={{ urlencode('Hi, I\'m interested in ' . $listing->title) }}"
                   target="_blank"
                   class="flex items-center justify-center w-11 h-11 rounded-xl bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366]/20 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chat</span>
                </a>
            @endif
            <button onclick="document.getElementById('inquiry-section').scrollIntoView({behavior: 'smooth'})"
                    class="bg-primary hover:bg-primary/90 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm shadow-primary/20">
                {{ $mobileCta ?? 'Enquire Now' }}
            </button>
        </div>
    </div>
</div>

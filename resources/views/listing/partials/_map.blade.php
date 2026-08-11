<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <h2 class="text-xl font-bold text-slate-900 mb-1">Location</h2>
    @if($listing->address || $listing->area)
        <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px] text-primary" aria-hidden="true">location_on</span>
            {{ implode(', ', array_filter([$listing->address, $listing->area?->name, 'Alibaug'])) }}
        </p>
    @else
        <p class="text-sm text-slate-500 mb-4">Alibaug, Maharashtra</p>
    @endif
    <div class="rounded-xl overflow-hidden w-full h-[280px] bg-slate-100">
        <iframe
            width="100%" height="100%"
            style="border:0;" loading="lazy" allowfullscreen
            src="https://maps.google.com/maps?q={{ urlencode(($listing->address ? $listing->address . ', ' : '') . ($listing->area?->name ? $listing->area->name . ', ' : '') . 'Alibaug, Maharashtra, India') }}&t=&z=14&ie=UTF8&iwloc=&output=embed">
        </iframe>
    </div>

    @if($listing->google_business_url)
        {{-- Owner-supplied Google Business Profile — opens in a new tab so the
             visitor doesn't lose the listing they were reading. --}}
        <a href="{{ $listing->google_business_url }}" target="_blank" rel="noopener nofollow"
           class="mt-4 w-full inline-flex items-center justify-center gap-2 border border-slate-200 hover:border-primary hover:bg-primary/5 text-slate-700 hover:text-primary font-bold text-sm px-5 py-3 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-[19px]" aria-hidden="true">storefront</span>
            View on Google
            <span class="material-symbols-outlined text-[16px] text-slate-400" aria-hidden="true">open_in_new</span>
        </a>
        <p class="text-[11px] text-slate-500 mt-2 text-center">Opens the business's Google profile for reviews, photos and directions.</p>
    @endif
</div>

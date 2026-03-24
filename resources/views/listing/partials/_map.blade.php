<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <h2 class="text-xl font-bold text-slate-900 mb-1">Location</h2>
    @if($listing->address || $listing->area)
        <p class="text-sm text-slate-500 mb-4 flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px] text-primary">location_on</span>
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
</div>

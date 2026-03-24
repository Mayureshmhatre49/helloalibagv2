@if($listing->amenities->count() > 0)
@php $amenityCount = $listing->amenities->count(); @endphp
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" x-data="{ showAll: false }">
    <h2 class="text-xl font-bold text-slate-900 mb-4">{{ $amenitiesHeading ?? "What's included" }}</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
        @foreach($listing->amenities->take(9) as $amenity)
            <div class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0">{{ $amenity->icon }}</span>
                <span class="text-sm text-slate-700 font-medium leading-tight">{{ $amenity->name }}</span>
            </div>
        @endforeach
        @if($amenityCount > 9)
            <template x-if="showAll">
                <div class="contents">
                    @foreach($listing->amenities->skip(9) as $amenity)
                        <div class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition-colors">
                            <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0">{{ $amenity->icon }}</span>
                            <span class="text-sm text-slate-700 font-medium leading-tight">{{ $amenity->name }}</span>
                        </div>
                    @endforeach
                </div>
            </template>
        @endif
    </div>
    @if($amenityCount > 9)
        <button @click="showAll = !showAll"
                class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-slate-700 border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 rounded-xl transition-all shadow-sm">
            <span class="material-symbols-outlined text-[16px]" x-text="showAll ? 'expand_less' : 'expand_more'">expand_more</span>
            <span x-text="showAll ? 'Show fewer' : 'Show all {{ $amenityCount }}'">Show all {{ $amenityCount }}</span>
        </button>
    @endif
</div>
@endif

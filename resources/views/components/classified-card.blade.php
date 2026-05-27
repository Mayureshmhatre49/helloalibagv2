{{-- Marketplace item card. Prop: $item (Classified) --}}
@props(['item'])

<div class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-2xl hover:-translate-y-0.5 transition-all duration-300 flex flex-col h-full relative">

    <a href="{{ route('marketplace.show', $item) }}" class="absolute inset-0 z-10" aria-label="View {{ $item->title }}"></a>

    {{-- Image --}}
    <div class="relative aspect-[4/3] overflow-hidden flex-shrink-0 bg-slate-100">
        @if($item->getPrimaryImageUrl())
            <img src="{{ $item->getPrimaryImageUrl() }}" alt="{{ $item->title }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
        @else
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-slate-50">
                <span class="material-symbols-outlined text-4xl text-slate-300">image</span>
                <span class="text-xs text-slate-400">No photo</span>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-3 left-3 z-20 pointer-events-none flex flex-col gap-1">
            @if($item->is_featured)
                <span class="inline-flex items-center gap-1 bg-amber-500 text-white px-2.5 py-1 rounded-full text-[10px] font-bold shadow">
                    <span class="material-symbols-outlined text-[11px]" style="font-variation-settings:'FILL' 1">star</span> Featured
                </span>
            @endif
            @if($item->getConditionLabel())
                <span class="inline-flex items-center bg-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-[10px] font-medium">
                    {{ $item->getConditionLabel() }}
                </span>
            @endif
        </div>

        @if($item->isSold())
            <div class="absolute inset-0 z-20 bg-black/45 flex items-center justify-center">
                <span class="bg-white text-slate-900 px-4 py-1.5 rounded-full text-sm font-bold rotate-[-6deg] shadow-lg">SOLD</span>
            </div>
        @endif

        @if($item->area)
        <div class="absolute bottom-3 left-3 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white px-2.5 py-1 rounded-full text-[10px] font-medium">
                <span class="material-symbols-outlined text-[11px]">location_on</span>{{ $item->area->name }}
            </span>
        </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="p-4 flex flex-col flex-grow pointer-events-none">
        <h3 class="font-bold text-slate-900 truncate text-[15px] leading-snug mb-1">{{ $item->title }}</h3>
        <p class="text-xs text-slate-500 truncate mb-2.5">{{ $item->category->name ?? 'Marketplace' }}</p>

        <div class="mt-auto pt-3 border-t border-slate-100 flex items-center justify-between">
            @if($item->price)
                <div class="flex items-baseline gap-1.5">
                    <span class="font-bold text-slate-900 text-base">₹{{ number_format($item->price) }}</span>
                    @if($item->is_negotiable)<span class="text-[10px] text-emerald-600 font-semibold">Negotiable</span>@endif
                </div>
            @else
                <span class="text-sm text-slate-500 italic">Ask price</span>
            @endif
            <span class="text-[11px] text-primary font-bold flex items-center gap-0.5 group-hover:gap-1.5 transition-all">
                View <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </span>
        </div>
    </div>
</div>

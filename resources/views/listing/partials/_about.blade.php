@if($listing->description)
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6"
     x-data="{ expanded: false, hasOverflow: false }"
     x-init="$nextTick(() => { hasOverflow = $refs.descText.scrollHeight > $refs.descText.clientHeight })">
    <h2 class="text-xl font-bold text-slate-900 mb-4">{{ $descHeading ?? 'About this place' }}</h2>
    <div class="relative">
        <div x-ref="descText"
             :class="expanded ? '' : 'max-h-48 overflow-hidden'"
             class="text-slate-600 text-[15px] leading-relaxed whitespace-pre-line">{{ $listing->description }}</div>
        <div x-show="!expanded && hasOverflow"
             class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
    </div>
    <button x-show="hasOverflow || expanded"
            x-cloak
            @click="expanded = !expanded"
            class="mt-3 text-sm font-bold text-primary flex items-center gap-0.5 hover:gap-1.5 transition-all">
        <span x-text="expanded ? 'Show less' : 'Read more'">Read more</span>
        <span class="material-symbols-outlined text-[16px]" x-text="expanded ? 'expand_less' : 'expand_more'">expand_more</span>
    </button>
</div>
@endif

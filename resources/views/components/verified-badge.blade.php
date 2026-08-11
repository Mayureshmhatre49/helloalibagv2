@php
    $sizeMap = [
        'sm' => ['py' => 'py-0.5', 'px' => 'px-1.5', 'text' => 'text-[10px]', 'icon' => '12'],
        'md' => ['py' => 'py-1',   'px' => 'px-2',   'text' => 'text-xs',    'icon' => '14'],
        'lg' => ['py' => 'py-1.5', 'px' => 'px-2.5', 'text' => 'text-sm',    'icon' => '16'],
    ];
    $s = $sizeMap[$size] ?? $sizeMap['md'];
    $verifiedOn = $listing->verified_at?->format('M Y');
    $tooltipText = $verifiedOn
        ? "Verified by Hello Alibaug since {$verifiedOn}"
        : 'Verified by Hello Alibaug';
    $tooltipDetail = 'This listing has been reviewed in person by our team for accuracy, quality and safety.';
@endphp

<span class="relative inline-flex" x-data="{ showTip: false }">
    <button type="button"
            @mouseenter="showTip = true" @mouseleave="showTip = false"
            @focus="showTip = true" @blur="showTip = false"
            @click.stop="showTip = !showTip"
            class="inline-flex items-center gap-1 bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200 font-bold uppercase tracking-wider rounded-full whitespace-nowrap shadow-sm cursor-help {{ $s['py'] }} {{ $s['px'] }} {{ $s['text'] }}"
            aria-label="{{ $tooltipText }}">
        <span class="material-symbols-outlined text-emerald-500" style="font-variation-settings:'FILL' 1; font-size: {{ $s['icon'] }}px;" aria-hidden="true">verified</span>
        @if ($showLabel)<span>Verified</span>@endif
    </button>
    <div x-show="showTip" x-transition.opacity.duration.150ms
         class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-56 bg-slate-900 text-white text-[11px] leading-relaxed rounded-xl px-3.5 py-2.5 shadow-xl z-50 pointer-events-none"
         style="display: none;">
        <p class="font-bold mb-0.5">{{ $tooltipText }}</p>
        <p class="text-white/70">{{ $tooltipDetail }}</p>
        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-px border-4 border-transparent border-t-slate-900"></div>
    </div>
</span>

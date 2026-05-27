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
@endphp

<span class="inline-flex items-center gap-1 bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200 font-bold uppercase tracking-wider rounded-full whitespace-nowrap shadow-sm {{ $s['py'] }} {{ $s['px'] }} {{ $s['text'] }}"
      title="{{ $tooltipText }}"
      role="img"
      aria-label="{{ $tooltipText }}">
    <span class="material-symbols-outlined text-emerald-500" style="font-variation-settings:'FILL' 1; font-size: {{ $s['icon'] }}px;">verified</span>
    @if ($showLabel)<span>Verified</span>@endif
</span>

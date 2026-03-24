@php
    $images      = $listing->images->filter(fn($i) => ($i->image_type ?? 'gallery') !== 'menu')->values();
    $totalImages = $images->count();
    $mainImage   = $images->first();
    $gridImages  = $images->skip(1)->take(4)->values();
    $extraCount  = max(0, $totalImages - 5);
@endphp

<div class="mb-6" x-data="{ current: 0, open: false }"
     @keydown.escape.window="open = false"
     @keydown.arrowleft.window="if(open) current = current > 0 ? current - 1 : {{ max(0, $totalImages - 1) }}"
     @keydown.arrowright.window="if(open) current = current < {{ max(0, $totalImages - 1) }} ? current + 1 : 0">

    @if($totalImages === 0)
        <div class="aspect-[16/9] bg-slate-100 rounded-3xl flex flex-col items-center justify-center text-slate-300">
            <span class="material-symbols-outlined text-6xl mb-2">image</span>
            <p class="text-sm">No photos yet</p>
        </div>
    @elseif($totalImages === 1)
        <div class="aspect-[16/9] rounded-3xl overflow-hidden cursor-zoom-in" @click="open = true; current = 0">
            <img src="{{ $mainImage->url }}" alt="{{ $listing->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3">
            {{-- Main image --}}
            <div class="relative cursor-zoom-in overflow-hidden group aspect-square w-full rounded-xl sm:rounded-3xl" @click="open = true; current = 0">
                <img src="{{ $mainImage->url }}" alt="{{ $listing->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
            </div>

            @if($gridImages->count() === 1)
                <div class="relative cursor-zoom-in overflow-hidden group aspect-square w-full rounded-xl sm:rounded-3xl" @click="open = true; current = 1">
                    <img src="{{ $gridImages[0]->url }}" alt="{{ $listing->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                </div>
            @elseif($gridImages->count() === 2)
                <div class="grid grid-rows-2 gap-2 sm:gap-3 aspect-square w-full">
                    @foreach($gridImages as $i => $image)
                        <div class="relative cursor-zoom-in overflow-hidden group w-full h-full rounded-xl sm:rounded-3xl" @click="open = true; current = {{ $i + 1 }}">
                            <img src="{{ $image->url }}" alt="{{ $listing->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-2 grid-rows-2 gap-2 sm:gap-3 aspect-square w-full">
                    @foreach($gridImages as $i => $image)
                        @php $isLast = ($i === 3); @endphp
                        <div class="relative cursor-zoom-in overflow-hidden group w-full h-full rounded-xl sm:rounded-3xl" @click="open = true; current = {{ $i + 1 }}">
                            <img src="{{ $image->url }}" alt="{{ $listing->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($isLast && $extraCount > 0)
                                <div class="absolute inset-0 bg-black/55 flex flex-col items-center justify-center text-white backdrop-blur-[1px]">
                                    <span class="material-symbols-outlined text-4xl mb-1" style="font-variation-settings:'FILL' 1">photo_library</span>
                                    <span class="text-3xl font-bold">+{{ $extraCount }}</span>
                                    <span class="text-sm font-medium mt-1 opacity-80">more photos</span>
                                </div>
                            @else
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex justify-end mt-2">
            <button @click="open = true; current = 0"
                class="flex items-center gap-2 text-sm font-bold text-slate-700 border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 rounded-xl shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                View all {{ $totalImages }} photos
            </button>
        </div>
    @endif

    {{-- Fullscreen Gallery Lightbox --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex flex-col overflow-hidden">

        <div class="absolute inset-0 z-0" @click="open = false">
            @foreach($images as $idx => $image)
                <div x-show="current === {{ $idx }}"
                     x-transition:enter="transition-opacity ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="absolute inset-0"
                     style="background-image: url('{{ $image->url }}'); background-size: cover; background-position: center; filter: blur(28px) saturate(1.4); transform: scale(1.15);"></div>
            @endforeach
            <div class="absolute inset-0 bg-black/65"></div>
            <div class="absolute inset-0" style="background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.7) 100%);"></div>
        </div>

        <div class="relative z-20 flex items-center justify-between px-5 py-3 flex-shrink-0"
             style="background: rgba(0,0,0,0.25); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden border border-white/20 flex-shrink-0">
                    @if($images->first())
                        <img src="{{ $images->first()->url }}" alt="" class="w-full h-full object-cover opacity-70">
                    @endif
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-none truncate max-w-[220px]">{{ $listing->title }}</p>
                    <p class="text-white/50 text-xs mt-0.5">
                        <span class="text-white/80 font-bold" x-text="current + 1"></span>
                        <span> of {{ $totalImages }} photos</span>
                    </p>
                </div>
            </div>
            <button @click="open = false"
                class="w-9 h-9 rounded-full flex items-center justify-center text-white/70 hover:text-white transition-colors"
                style="background: rgba(255,255,255,0.1); backdrop-filter: blur(4px);">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <div class="relative z-10 flex-1 flex items-center justify-center min-h-0 px-16 py-4" @click.self="open = false">
            @foreach($images as $idx => $image)
                <img x-show="current === {{ $idx }}"
                     src="{{ $image->url }}" alt="{{ $image->alt_text ?? $listing->title }}"
                     class="max-w-full max-h-full object-contain select-none"
                     style="border-radius: 12px; box-shadow: 0 32px 80px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.06);"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-[0.96]" x-transition:enter-end="opacity-100 scale-100">
            @endforeach
            <button @click="current = current > 0 ? current - 1 : {{ $totalImages - 1 }}"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full text-white flex items-center justify-center transition-all duration-200 group"
                style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);">
                <span class="material-symbols-outlined text-[26px] group-hover:scale-110 transition-transform">chevron_left</span>
            </button>
            <button @click="current = current < {{ $totalImages - 1 }} ? current + 1 : 0"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full text-white flex items-center justify-center transition-all duration-200 group"
                style="background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15);">
                <span class="material-symbols-outlined text-[26px] group-hover:scale-110 transition-transform">chevron_right</span>
            </button>
        </div>

        <div class="relative z-20 flex-shrink-0 px-4 py-3"
             style="background: rgba(0,0,0,0.30); backdrop-filter: blur(12px); border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="flex gap-2 justify-center overflow-x-auto scrollbar-none">
                @foreach($images as $idx => $image)
                    <button @click="current = {{ $idx }}"
                        class="flex-shrink-0 w-16 h-11 rounded-lg overflow-hidden transition-all duration-200"
                        :class="current === {{ $idx }} ? 'ring-2 ring-white opacity-100 scale-105' : 'ring-1 ring-white/20 opacity-40 hover:opacity-75'">
                        <img src="{{ $image->url }}" alt="" class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

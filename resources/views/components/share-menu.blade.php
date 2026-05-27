@if ($variant === 'inline-row')
    {{-- ── INLINE ROW VARIANT ── For listing pages, blog detail, etc. ─── --}}
    <div class="flex flex-wrap items-center gap-2"
         x-data="{
             copied: false,
             webShare() {
                 if (navigator.share) {
                     navigator.share({ title: document.title, text: @js($shareText), url: @js($shareUrl) }).catch(() => {});
                 } else {
                     this.copyLink();
                 }
             },
             copyLink() {
                 navigator.clipboard.writeText(@js($shareUrl));
                 this.copied = true;
                 setTimeout(() => { this.copied = false }, 2000);
             },
         }">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            WhatsApp
        </a>

        <button @click="webShare()" type="button"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-[14px]">share</span>
            <span class="sm:inline">Share</span>
        </button>

        <button @click="copyLink()" type="button"
                class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl transition-colors relative">
            <span class="material-symbols-outlined text-[14px]" x-text="copied ? 'check' : 'link'">link</span>
            <span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
        </button>
    </div>

@else
    {{-- ── BUTTON VARIANT ── Dropdown menu (default) ──────────────────── --}}
    <div class="relative inline-block"
         x-data="{
             open: false,
             copied: false,
             webShare() {
                 if (navigator.share) {
                     navigator.share({ title: document.title, text: @js($shareText), url: @js($shareUrl) }).catch(() => {});
                     this.open = false;
                 } else {
                     this.copyLink();
                 }
             },
             copyLink() {
                 navigator.clipboard.writeText(@js($shareUrl));
                 this.copied = true;
                 setTimeout(() => { this.copied = false }, 2000);
             },
         }"
         @click.outside="open = false">
        <button @click="open = !open" type="button"
                class="inline-flex items-center gap-1.5 bg-white border border-border-light hover:border-slate-300 text-slate-700 text-xs sm:text-sm font-bold px-3 py-2 rounded-xl transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px]">share</span>
            <span>{{ $label }}</span>
            <span class="material-symbols-outlined text-[16px] transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             style="display: none;"
             class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50 origin-top-right">
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#22c55e" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                WhatsApp
            </a>
            <a href="{{ $twitterUrl }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#0d161b" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                X (Twitter)
            </a>
            <a href="{{ $facebookUrl }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="#1877f2" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
            <a href="{{ $emailUrl }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                <span class="material-symbols-outlined text-[16px] text-slate-500">mail</span>
                Email
            </a>
            <div class="border-t border-slate-100 mt-1 pt-1">
                <button @click="copyLink()" type="button"
                        class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[16px] text-slate-500" x-text="copied ? 'check' : 'link'">link</span>
                    <span x-text="copied ? 'Copied to clipboard!' : 'Copy link'">Copy link</span>
                </button>
            </div>
        </div>
    </div>
@endif

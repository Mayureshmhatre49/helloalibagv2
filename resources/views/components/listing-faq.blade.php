@if (count($faqs) > 0)
    <section class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-7" aria-labelledby="listing-faq-heading">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-primary text-[22px]">help</span>
            </div>
            <div>
                <h2 id="listing-faq-heading" class="text-slate-900 font-bold text-xl leading-tight">Frequently asked</h2>
                <p class="text-text-secondary text-xs mt-0.5">About {{ $listing->title }}</p>
            </div>
        </div>

        <div class="space-y-2.5" x-data="{ open: null }">
            @foreach ($faqs as $i => $faq)
                <div class="bg-slate-50 border border-slate-100 rounded-xl overflow-hidden hover:border-slate-200 transition-colors">
                    <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                            type="button"
                            class="w-full text-left px-4 py-3.5 flex items-center justify-between gap-4 hover:bg-slate-100/60 transition-colors"
                            :aria-expanded="open === {{ $i }} ? 'true' : 'false'">
                        <span class="text-slate-900 font-semibold text-sm pr-2 leading-snug">{{ $faq['q'] }}</span>
                        <span class="material-symbols-outlined text-slate-400 transition-transform duration-200 flex-shrink-0 text-[20px]"
                              :class="open === {{ $i }} ? 'rotate-180 text-primary' : ''">expand_more</span>
                    </button>
                    <div x-show="open === {{ $i }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;"
                         class="px-4 pb-4 pt-1 text-slate-700 text-sm leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- FAQPage schema.org JSON-LD — drives Google rich snippets. --}}
    @push('scripts')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            @foreach ($faqs as $i => $faq)
            {
                "@type": "Question",
                "name": @json($faq['q']),
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": @json($faq['a'])
                }
            }@if (!$loop->last),@endif
            @endforeach
        ]
    }
    </script>
    @endpush
@endif

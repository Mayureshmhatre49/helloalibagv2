{{--
  Owner Tour Guide — step-by-step popup walkthrough
  Include in: layouts/dashboard.blade.php
  Trigger: ?tour=1 query param OR session('show_tour')
--}}
@if(session('show_tour') || request('tour') == '1')
<div
    x-data="{
        open: true,
        step: 0,
        steps: [
            {
                icon: 'waving_hand',
                color: 'text-primary',
                bg: 'bg-primary/10',
                title: 'Welcome to Your Dashboard! 🎉',
                desc: 'This is your command centre for managing your Hello Alibaug listing. Let us walk you through the key features in just a few steps.',
                target: null
            },
            {
                icon: 'list',
                color: 'text-blue-600',
                bg: 'bg-blue-50',
                title: 'My Listings',
                desc: 'Here you can view, edit, and manage all your listings. You can also track their approval status and add new photos or details anytime.',
                target: 'tour-listings'
            },
            {
                icon: 'mail',
                color: 'text-purple-600',
                bg: 'bg-purple-50',
                title: 'Inquiries',
                desc: 'When visitors contact you through your listing, their messages land here. Reply promptly to convert leads into bookings!',
                target: 'tour-inquiries'
            },
            {
                icon: 'star',
                color: 'text-amber-500',
                bg: 'bg-amber-50',
                title: 'Reviews',
                desc: 'Customers who visit your business can leave reviews and ratings. You can reply to reviews to show you care about feedback.',
                target: 'tour-reviews'
            },
            {
                icon: 'event_available',
                color: 'text-green-600',
                bg: 'bg-green-50',
                title: 'Availability Calendar',
                desc: 'Block dates or set your availability so customers know when you are open. Perfect for villas, homestays, and experience providers.',
                target: 'tour-availability'
            },
            {
                icon: 'bar_chart',
                color: 'text-indigo-600',
                bg: 'bg-indigo-50',
                title: 'Analytics & Insights',
                desc: 'Track your listing\'s performance — views, inquiry counts, top days, and rating trends — all from your dashboard.',
                target: 'tour-analytics'
            },
            {
                icon: 'support_agent',
                color: 'text-teal-600',
                bg: 'bg-teal-50',
                title: 'Support',
                desc: 'Have a question or need help? Open a support ticket and our team will get back to you within 24 hours.',
                target: 'tour-support'
            },
            {
                icon: 'check_circle',
                color: 'text-green-600',
                bg: 'bg-green-50',
                title: 'You\'re All Set! 🚀',
                desc: 'Your Free plan is active and your listing is being reviewed. We\'ll notify you once it\'s approved. Happy listing!',
                target: null
            }
        ],
        get current() { return this.steps[this.step]; },
        next() { if (this.step < this.steps.length - 1) this.step++; else this.close(); },
        prev() { if (this.step > 0) this.step--; },
        close() {
            this.open = false;
            // Mark tour as seen via fetch so it doesn't show again
            fetch('{{ route('tour.dismiss') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' } });
        }
    }"
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
>
    {{-- Modal Box --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative overflow-hidden"
        @click.stop
    >
        {{-- Progress bar --}}
        <div class="h-1 bg-slate-100">
            <div class="h-full bg-primary transition-all duration-500 ease-out"
                 :style="`width: ${((step + 1) / steps.length) * 100}%`"></div>
        </div>

        {{-- Close button --}}
        <button @click="close()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors z-10">
            <span class="material-symbols-outlined text-slate-500 text-[18px]">close</span>
        </button>

        {{-- Content --}}
        <div class="p-8">
            {{-- Step indicator --}}
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-5">
                Step <span x-text="step + 1"></span> of <span x-text="steps.length"></span>
            </p>

            {{-- Icon --}}
            <div class="mb-5 flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                     :class="current.bg">
                    <span class="material-symbols-outlined text-3xl" :class="current.color"
                          x-text="current.icon" style="font-variation-settings: 'FILL' 1"></span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 mb-2" x-text="current.title"></h2>
                    <p class="text-sm text-slate-500 leading-relaxed" x-text="current.desc"></p>
                </div>
            </div>

            {{-- Step dots --}}
            <div class="flex gap-1.5 mb-6 justify-center">
                <template x-for="(s, i) in steps" :key="i">
                    <button @click="step = i"
                        class="transition-all duration-300 rounded-full"
                        :class="i === step ? 'w-6 h-2 bg-primary' : 'w-2 h-2 bg-slate-200 hover:bg-slate-300'">
                    </button>
                </template>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <button x-show="step > 0" @click="prev()"
                    class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back
                </button>
                <button @click="next()"
                    class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-sm transition-all shadow-md shadow-primary/20">
                    <span x-text="step === steps.length - 1 ? 'Finish Tour 🚀' : 'Next'"></span>
                    <span x-show="step < steps.length - 1" class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </div>
            <button @click="close()" class="w-full text-center text-xs text-slate-400 mt-3 hover:text-slate-600 transition-colors">
                Skip tour
            </button>
        </div>
    </div>
</div>
@endif

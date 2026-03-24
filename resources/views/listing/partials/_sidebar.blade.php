{{--
  Shared sidebar partial.
  Variables:
    $sidebarCtaLabel  — primary button label (default "Send Inquiry")
    $sidebarShowDates — bool, show check-in/out date pickers
    $sidebarDateLabel — label for first date (default "Check-in")
    $sidebarDate2Label — label for second date (default "Check-out")
    $sidebarShowGuests — bool, show guests field
    $sidebarGuestsLabel — label (default "Number of guests")
    $sidebarShowQuote  — bool, show "Request a Quote" style layout
    $sidebarExtra      — additional blade slot included above inquiry form
--}}
@php
    $sidebarCtaLabel   = $sidebarCtaLabel   ?? 'Send Inquiry';
    $sidebarShowDates  = $sidebarShowDates  ?? false;
    $sidebarDateLabel  = $sidebarDateLabel  ?? 'Check-in';
    $sidebarDate2Label = $sidebarDate2Label ?? 'Check-out';
    $sidebarShowGuests = $sidebarShowGuests ?? false;
    $sidebarGuestsLabel = $sidebarGuestsLabel ?? 'Number of guests';
@endphp

<aside class="lg:w-[360px] flex-shrink-0" id="inquiry-section">
    <div class="lg:sticky lg:top-24 space-y-4">

        {{-- Price + CTA Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/50 p-6">

            {{-- Price --}}
            @if($listing->price)
                <div class="mb-4 pb-4 border-b border-slate-100">
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-3xl font-bold text-slate-900">₹{{ number_format($listing->price) }}</span>
                        @if($priceLabel)
                            <span class="text-slate-500 text-sm">{{ $priceLabel }}</span>
                        @endif
                    </div>
                    @if($avgRating > 0)
                        <div class="flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-amber-400 text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                            <span class="text-sm font-bold text-slate-800">{{ $avgRating }}</span>
                            <span class="text-sm text-slate-400">· {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="mb-4 pb-4 border-b border-slate-100">
                    <p class="text-lg font-semibold text-slate-700">Contact for pricing</p>
                    @if($avgRating > 0)
                        <div class="flex items-center gap-1 mt-1">
                            <span class="material-symbols-outlined text-amber-400 text-[14px]" style="font-variation-settings:'FILL' 1">star</span>
                            <span class="text-sm font-bold text-slate-800">{{ $avgRating }}</span>
                            <span class="text-sm text-slate-400">· {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Extra slot (e.g. quick stats for eat, capacity for events) --}}
            @isset($sidebarTopSlot)
                {!! $sidebarTopSlot !!}
            @endisset

            {{-- Contact Buttons --}}
            <div class="space-y-2.5 mb-5">
                @if($listing->phone)
                    <a href="tel:{{ $listing->phone }}"
                       class="flex items-center justify-center gap-2 w-full bg-primary text-white py-3 rounded-xl font-bold text-sm hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                        <span class="material-symbols-outlined text-[20px]">call</span>
                        Call Now
                    </a>
                @endif
                @if($listing->whatsapp)
                    <a href="https://wa.me/91{{ $listing->whatsapp }}?text={{ urlencode('Hi, I\'m interested in ' . $listing->title . ' – ' . url()->current()) }}"
                       target="_blank"
                       class="flex items-center justify-center gap-2 w-full bg-[#25D366] text-white py-3 rounded-xl font-bold text-sm hover:bg-[#1db954] transition-colors shadow-md shadow-green-500/20">
                        <span class="material-symbols-outlined text-[20px]">chat</span>
                        Chat on WhatsApp
                    </a>
                @endif
                @if($listing->email && !$listing->phone && !$listing->whatsapp)
                    <a href="mailto:{{ $listing->email }}"
                       class="flex items-center justify-center gap-2 w-full bg-white border-2 border-slate-200 text-slate-700 py-3 rounded-xl font-bold text-sm hover:border-primary hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]">mail</span>
                        Send Email
                    </a>
                @endif
            </div>

            {{-- Divider --}}
            <div class="relative flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-slate-100"></div>
                <span class="text-xs text-slate-400 font-medium">or send an inquiry</span>
                <div class="flex-1 h-px bg-slate-100"></div>
            </div>

            {{-- Inquiry Form --}}
            <form method="POST" action="{{ route('listing.inquiry.store', $listing) }}" class="space-y-2.5">
                @csrf
                <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}" required
                       placeholder="Your name *"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}" required
                       placeholder="Email address *"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       placeholder="Phone number"
                       class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">

                @if($sidebarShowDates)
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1 px-1">{{ $sidebarDateLabel }}</label>
                            <input type="date" name="check_in" value="{{ old('check_in') }}"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-slate-50/50">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1 px-1">{{ $sidebarDate2Label }}</label>
                            <input type="date" name="check_out" value="{{ old('check_out') }}"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none bg-slate-50/50">
                        </div>
                    </div>
                @endif

                @if($sidebarShowGuests)
                    <input type="number" name="guests" min="1" max="50" value="{{ old('guests') }}"
                           placeholder="{{ $sidebarGuestsLabel }}"
                           class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none placeholder:text-slate-400 bg-slate-50/50">
                @endif

                <textarea name="message" rows="3" required
                          placeholder="Tell us what you're looking for... *"
                          class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none placeholder:text-slate-400 bg-slate-50/50">{{ old('message') }}</textarea>
                <button type="submit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl text-sm font-bold transition-colors shadow-md">
                    {{ $sidebarCtaLabel }}
                </button>
            </form>

            <p class="flex items-start gap-1.5 text-[11px] text-slate-400 mt-3 leading-snug">
                <span class="material-symbols-outlined text-[13px] mt-0.5 flex-shrink-0">info</span>
                This sends an inquiry request. The owner will confirm availability and contact you directly. Payment is arranged with the property.
            </p>

            {{-- Trust signals --}}
            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-2 text-center">
                <div>
                    <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">verified</span>
                    <p class="text-[10px] text-slate-500 leading-tight">Verified<br>Listing</p>
                </div>
                <div>
                    <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">lock</span>
                    <p class="text-[10px] text-slate-500 leading-tight">Secure<br>Inquiry</p>
                </div>
                <div>
                    <span class="material-symbols-outlined text-primary text-[20px] block mb-0.5">support_agent</span>
                    <p class="text-[10px] text-slate-500 leading-tight">Local<br>Support</p>
                </div>
            </div>
        </div>

        {{-- Owner Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Listed by</p>
            <div class="flex items-center gap-3">
                <img src="{{ $listing->creator->getAvatarUrl() }}" alt="{{ $listing->creator->name }}"
                     class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-900 text-sm truncate">{{ $listing->creator->name }}</p>
                    <p class="text-xs text-slate-400">Member since {{ $listing->creator->created_at->format('M Y') }}</p>
                </div>
                <span class="material-symbols-outlined text-primary text-[20px]" title="Verified owner"
                      style="font-variation-settings:'FILL' 1">verified</span>
            </div>
        </div>

    </div>
</aside>

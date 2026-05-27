<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" id="reviews">
    <h2 class="text-xl font-bold text-slate-900 mb-5">
        @if($avgRating > 0)
            <span class="inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-amber-400 text-[22px]" style="font-variation-settings:'FILL' 1">star</span>
                {{ $avgRating }} · {{ $reviewCount }} {{ Str::plural('Review', $reviewCount) }}
            </span>
        @else
            Reviews
        @endif
    </h2>

    {{-- Rating bar chart --}}
    @if($reviewCount > 0)
        <div class="flex items-start gap-8 mb-6 p-5 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="text-center flex-shrink-0">
                <div class="text-5xl font-bold text-slate-900 leading-none mb-1">{{ $avgRating }}</div>
                <div class="flex items-center justify-center gap-0.5 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="material-symbols-outlined text-[14px] {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}"
                              style="{{ $i <= round($avgRating) ? 'font-variation-settings:\'FILL\' 1' : '' }}">star</span>
                    @endfor
                </div>
                <p class="text-xs text-slate-400">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
            </div>
            <div class="flex-1">
                @for($star = 5; $star >= 1; $star--)
                    @php
                        $starCount = $listing->approvedReviews->where('rating', $star)->count();
                        $pct = $reviewCount > 0 ? round(($starCount / $reviewCount) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-xs text-slate-500 w-2.5 text-right">{{ $star }}</span>
                        <span class="material-symbols-outlined text-[12px] text-amber-400" style="font-variation-settings:'FILL' 1">star</span>
                        <div class="flex-1 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width:{{ $pct }}%;"></div>
                        </div>
                        <span class="text-xs text-slate-400 w-4 text-right">{{ $starCount }}</span>
                    </div>
                @endfor
            </div>
        </div>
    @endif

    {{-- Review form --}}
    @auth
        @php
            $hasInquired = \App\Models\Inquiry::where('listing_id', $listing->id)->where('user_id', auth()->id())->exists();
            $hasBooked = \App\Models\Booking::where('listing_id', $listing->id)->where('user_id', auth()->id())->whereIn('status', ['confirmed', 'completed'])->exists();
            $canReview = ($hasInquired || $hasBooked) && auth()->id() !== $listing->created_by && !$listing->reviews()->where('user_id', auth()->id())->exists();
        @endphp
        @if($canReview)
            <div class="mb-8 p-5 bg-amber-50 rounded-2xl border border-amber-100"
                 x-data="{
                     rating: 5,
                     previews: [],
                     handleFiles(event) {
                         this.previews = [];
                         const files = Array.from(event.target.files || []).slice(0, 5);
                         files.forEach(file => {
                             if (!file.type.startsWith('image/')) return;
                             const reader = new FileReader();
                             reader.onload = (e) => this.previews.push({ url: e.target.result, name: file.name });
                             reader.readAsDataURL(file);
                         });
                     },
                     clearPhotos() {
                         this.previews = [];
                         this.$refs.photoInput.value = '';
                     },
                 }">
                <h3 class="font-bold text-slate-900 mb-4">Share your experience</h3>
                <form action="{{ route('listing.review.store', $listing) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <p class="text-sm font-medium text-slate-600 mb-2">Your rating</p>
                        <div class="flex items-center gap-1">
                            @for($s = 1; $s <= 5; $s++)
                                <button type="button" @click="rating = {{ $s }}"
                                        class="transition-all duration-150 hover:scale-110 focus:outline-none"
                                        :class="rating >= {{ $s }} ? 'text-amber-400' : 'text-slate-200'">
                                    <span class="material-symbols-outlined text-3xl"
                                          :style="rating >= {{ $s }} ? 'font-variation-settings:\'FILL\' 1' : ''">star</span>
                                </button>
                            @endfor
                            <span class="ml-2 text-sm text-slate-500"
                                  x-text="['', 'Terrible', 'Poor', 'Average', 'Very Good', 'Excellent'][rating]"></span>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                    </div>

                    <div class="mb-3">
                        <textarea name="comment" required rows="3"
                                  class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none placeholder:text-slate-400"
                                  placeholder="What did you enjoy? What could be improved?">{{ old('comment') }}</textarea>
                    </div>

                    {{-- Photo upload --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-slate-600">Add photos <span class="text-text-secondary text-xs font-normal">(optional, up to 5)</span></p>
                            <button type="button" x-show="previews.length > 0" @click="clearPhotos()"
                                    class="text-xs font-bold text-text-secondary hover:text-red-600 transition-colors inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">close</span> Clear
                            </button>
                        </div>

                        <label for="review-photo-input" class="block cursor-pointer">
                            <div class="border-2 border-dashed border-amber-200 rounded-xl px-4 py-5 text-center hover:border-amber-400 hover:bg-amber-100/30 transition-colors">
                                <span class="material-symbols-outlined text-amber-500 text-[28px] block mb-1">add_a_photo</span>
                                <p class="text-sm font-semibold text-slate-700">Drop photos or click to upload</p>
                                <p class="text-xs text-text-secondary mt-1">JPG, PNG or WebP · max 8MB each · 5 photos max</p>
                            </div>
                            <input id="review-photo-input" x-ref="photoInput" type="file" name="photos[]"
                                   accept="image/jpeg,image/png,image/webp" multiple
                                   class="hidden" @change="handleFiles($event)">
                        </label>

                        <div x-show="previews.length > 0" class="grid grid-cols-5 gap-2 mt-3">
                            <template x-for="(p, i) in previews" :key="i">
                                <div class="aspect-square rounded-lg overflow-hidden bg-white border border-amber-200">
                                    <img :src="p.url" :alt="p.name" class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>

                        @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="bg-primary text-white font-bold px-6 py-2.5 rounded-xl hover:bg-primary/90 transition-colors text-sm shadow-sm shadow-primary/20">
                        Submit Review
                    </button>
                </form>
            </div>
        @elseif(!($hasInquired || $hasBooked) && auth()->id() !== $listing->created_by)
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
                <span class="material-symbols-outlined text-blue-400 text-[20px] flex-shrink-0 mt-0.5">verified_user</span>
                <p class="text-sm text-slate-600">Only guests who have <strong class="text-slate-800">booked</strong> or <strong class="text-slate-800">inquired</strong> can leave a review — keeping all reviews authentic.</p>
            </div>
        @endif
    @else
        <div class="flex items-center gap-3 bg-amber-50 rounded-2xl p-4 mb-6 border border-amber-100">
            <span class="material-symbols-outlined text-amber-400 text-[20px] flex-shrink-0">info</span>
            <p class="text-sm text-slate-600">
                <a href="{{ route('login') }}" class="font-bold text-primary hover:underline">Log in</a> to leave a review.
            </p>
        </div>
    @endauth

    {{-- Review list --}}
    <div class="space-y-5">
        @forelse($listing->approvedReviews as $review)
            <div class="flex gap-4 {{ !$loop->last ? 'pb-5 border-b border-slate-100' : '' }}">
                <img src="{{ $review->user->getAvatarUrl() }}" alt="{{ $review->user->name }}"
                     class="w-11 h-11 rounded-full object-cover flex-shrink-0 border-2 border-white shadow">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1 gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</span>
                        <p class="text-xs text-slate-400 flex-shrink-0">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-0.5 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[14px] {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"
                                  style="{{ $i <= $review->rating ? 'font-variation-settings:\'FILL\' 1' : '' }}">star</span>
                        @endfor
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>

                    @if ($review->relationLoaded('photos') ? $review->photos->isNotEmpty() : $review->photos()->exists())
                        @php $photos = $review->relationLoaded('photos') ? $review->photos : $review->photos()->get(); @endphp
                        <div class="mt-3 grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 gap-2"
                             x-data="{ open: false, currentIndex: 0, photos: @js($photos->map(fn ($p) => ['url' => $p->url(), 'thumb' => $p->thumbnailUrl()])->all()) }">
                            @foreach ($photos as $i => $photo)
                                <button type="button"
                                        @click="currentIndex = {{ $i }}; open = true"
                                        class="group aspect-square rounded-lg overflow-hidden bg-slate-100 border border-slate-100 hover:border-primary/40 transition-all focus:outline-none focus:ring-2 focus:ring-primary/30">
                                    <img src="{{ $photo->thumbnailUrl() }}" alt="Review photo by {{ $review->user?->name }}" loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </button>
                            @endforeach

                            {{-- Lightbox --}}
                            <div x-show="open" x-cloak
                                 x-transition.opacity
                                 @keydown.escape.window="open = false"
                                 @keydown.arrow-left.window="if (open) currentIndex = (currentIndex - 1 + photos.length) % photos.length"
                                 @keydown.arrow-right.window="if (open) currentIndex = (currentIndex + 1) % photos.length"
                                 class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4"
                                 @click.self="open = false"
                                 style="display: none;">
                                <button type="button" @click="open = false"
                                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition-colors"
                                        aria-label="Close">
                                    <span class="material-symbols-outlined">close</span>
                                </button>

                                <button type="button" x-show="photos.length > 1"
                                        @click.stop="currentIndex = (currentIndex - 1 + photos.length) % photos.length"
                                        class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition-colors"
                                        aria-label="Previous">
                                    <span class="material-symbols-outlined text-[28px]">chevron_left</span>
                                </button>
                                <button type="button" x-show="photos.length > 1"
                                        @click.stop="currentIndex = (currentIndex + 1) % photos.length"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition-colors"
                                        aria-label="Next">
                                    <span class="material-symbols-outlined text-[28px]">chevron_right</span>
                                </button>

                                <img :src="photos[currentIndex].url" alt="" class="max-w-full max-h-[88vh] object-contain rounded-xl shadow-2xl">

                                <p x-show="photos.length > 1" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/80 text-sm font-medium bg-black/40 px-3 py-1 rounded-full">
                                    <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- Owner Reply — prominent so trust signal is visible --}}
                    @if (!empty($review->owner_reply))
                        <div class="mt-4 ml-2 sm:ml-6 relative bg-gradient-to-br from-primary/5 via-white to-amber-50/40 border border-primary/15 rounded-2xl p-4 sm:p-5">
                            {{-- Decorative quote tab --}}
                            <span class="absolute -top-2.5 left-5 inline-flex items-center gap-1.5 bg-primary text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">
                                <span class="material-symbols-outlined text-[12px]" style="font-variation-settings:'FILL' 1">verified</span>
                                Owner replied
                            </span>
                            <div class="flex items-start gap-3 mt-1">
                                <img src="{{ $listing->creator?->getAvatarUrl() }}"
                                     alt="{{ $listing->creator?->name }}"
                                     class="w-10 h-10 rounded-full border-2 border-white shadow-sm flex-shrink-0 mt-0.5 object-cover">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-baseline gap-2 flex-wrap mb-1.5">
                                        <p class="text-slate-900 font-bold text-sm leading-tight">{{ $listing->creator?->name }}</p>
                                        <span class="text-[10px] text-text-secondary font-semibold uppercase tracking-wider">Listing host</span>
                                        @if (!empty($review->owner_replied_at))
                                            <span class="text-text-secondary text-xs">· {{ \Carbon\Carbon::parse($review->owner_replied_at)->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $review->owner_reply }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <span class="material-symbols-outlined text-5xl text-slate-200 mb-3 block">reviews</span>
                <p class="text-sm text-slate-400">No reviews yet. Be the first to share your experience!</p>
            </div>
        @endforelse
    </div>
</div>

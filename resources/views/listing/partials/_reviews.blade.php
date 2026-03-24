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
            <div class="mb-8 p-5 bg-amber-50 rounded-2xl border border-amber-100" x-data="{ rating: 5 }">
                <h3 class="font-bold text-slate-900 mb-4">Share your experience</h3>
                <form action="{{ route('listing.review.store', $listing) }}" method="POST">
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

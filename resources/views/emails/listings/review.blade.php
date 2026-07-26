<x-mail::message>

# ⭐ New Review on Your Listing

Hi **{{ $listing->creator->name }}**,

Great news — someone just left a review for your listing on Hello Alibaug!

<x-mail::panel>
**{{ $listing->title }}**
Reviewed by: **{{ $review->user->name }}**
Rating: @for ($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor ({{ $review->rating }}/5)
</x-mail::panel>

**What they said:**

> "{{ $review->comment }}"

---

Reviews help build trust with potential guests. We encourage you to **respond to this review** — a thoughtful reply shows you care about your guests' experience.

<x-mail::button :url="route('listing.show', [$listing->category->slug, $listing->slug]) . '#reviews'">
Read & Reply to Review →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

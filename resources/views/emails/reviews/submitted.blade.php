<x-mail::message>

# 🔔 New Review Awaiting Moderation

Hi **Admin**,

A new review has been submitted and is pending your review.

<x-mail::panel>
**{{ $review->listing->title }}**
Rating: {{ $review->rating }}/5
By: {{ $review->user->name }} ({{ $review->user->email }})
Submitted at: {{ $review->created_at->format('d M Y, h:i A') }}

{{ \Illuminate\Support\Str::limit($review->comment, 200) }}
</x-mail::panel>

<x-mail::button :url="route('admin.reviews.index')">
Review in Admin Panel →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

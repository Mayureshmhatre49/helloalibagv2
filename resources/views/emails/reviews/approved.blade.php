<x-mail::message>

# Your review is live!

Hi **{{ $review->user->name }}**,

Thanks for taking the time to share your feedback — your review of **"{{ $review->listing->title }}"** has been approved and is now visible to other travellers on Hello Alibaug.

<x-mail::panel>
{{ \Illuminate\Support\Str::limit($review->comment, 200) }}
</x-mail::panel>

<x-mail::button :url="route('listing.show', [$review->listing->category->slug, $review->listing->slug]) . '#reviews'">
View Your Review →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

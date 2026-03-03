<x-mail::message>
# New Review Received!

Hi {{ $listing->creator->name }},

You just received a new {{ $review->rating }}-star review for "**{{ $listing->title }}**" from {{ $review->user->name }}.

> "{{ $review->comment }}"

<x-mail::button :url="route('listing.show', [$listing->category->slug, $listing->slug]) . '#reviews'">
Read the Review
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

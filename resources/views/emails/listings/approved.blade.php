<x-mail::message>
# Congratulations!

Hi {{ $listing->creator->name }},

Great news! Your listing "**{{ $listing->title }}**" has been approved and is now live on our platform.

<x-mail::button :url="route('listing.show', [$listing->category->slug, $listing->slug])">
View Your Listing
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

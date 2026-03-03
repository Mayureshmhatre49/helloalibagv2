<x-mail::message>
# Update on Your Listing

Hi {{ $listing->creator->name }},

Thank you for submitting "**{{ $listing->title }}**". Unfortunately, we are unable to approve your listing at this time.

**Reason for rejection:**
> {{ $listing->rejection_reason }}

Please review the feedback and update your listing from your dashboard to resubmit for approval.

<x-mail::button :url="route('owner.listings.edit', $listing)">
Edit Listing
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

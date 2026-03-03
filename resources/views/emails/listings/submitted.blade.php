<x-mail::message>
# {{ $isAdmin ? 'New Listing Submitted' : 'Listing Submitted Successfully' }}

Hi {{ $isAdmin ? 'Admin' : $listing->creator->name }},

@if($isAdmin)
A new listing "**{{ $listing->title }}**" has been submitted for approval by {{ $listing->creator->name }}.
@else
Thank you for submitting your listing "**{{ $listing->title }}**". Our team will review it shortly. You'll receive another email once it's approved.
@endif

<x-mail::button :url="route($isAdmin ? 'admin.listings.index' : 'owner.dashboard')">
View {{ $isAdmin ? 'Approval Queue' : 'Dashboard' }}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

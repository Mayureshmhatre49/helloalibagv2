@component('mail::message')
# Reply to Your Inquiry

Hello {{ $inquiry->name }},

The owner of **{{ $inquiry->listing->title }}** has replied to your inquiry:

> {{ $inquiry->owner_reply }}

If you have further questions, you can reply directly to this email or visit the listing page.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

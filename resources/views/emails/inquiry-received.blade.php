@component('mail::message')
# New Inquiry Received

You have a new inquiry for **{{ $inquiry->listing->title }}**!

**From:** {{ $inquiry->name }}
**Email:** {{ $inquiry->email }}
@if($inquiry->phone)**Phone:** {{ $inquiry->phone }}@endif

@if($inquiry->check_in)
**Check-in:** {{ $inquiry->check_in->format('M d, Y') }}
@if($inquiry->check_out)**Check-out:** {{ $inquiry->check_out->format('M d, Y') }}@endif
@endif

**Message:**
> {{ $inquiry->message }}

@component('mail::button', ['url' => route('owner.inquiries.show', $inquiry)])
View & Reply
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

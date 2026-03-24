@component('mail::message')
# New Contact Form Submission

You have received a new message via the Hello Alibaug contact form.

**From:** {{ $senderName }}
**Email:** {{ $senderEmail }}
**Subject:** {{ $subject }}

**Message:**
> {!! nl2br(e($messageBody)) !!}

---

*Reply directly to this email to respond to {{ $senderName }}.*

Thanks,
{{ config('app.name') }}
@endcomponent

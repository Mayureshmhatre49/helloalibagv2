<x-mail::message>

# 📩 New Enquiry Received!

Hi **{{ $inquiry->listing->creator->name }}**,

You have a new enquiry for your listing on Hello Alibaug. Respond promptly to improve your chances of booking!

<x-mail::panel>
**Listing:** {{ $inquiry->listing->title }}
</x-mail::panel>

**Enquiry Details:**

| | |
|---|---|
| **Name** | {{ $inquiry->name }} |
| **Email** | {{ $inquiry->email }} |
@if($inquiry->phone)| **Phone** | {{ $inquiry->phone }} |
@endif@if($inquiry->check_in)| **Check-in** | {{ $inquiry->check_in->format('d M Y') }} |
@endif@if($inquiry->check_out)| **Check-out** | {{ $inquiry->check_out->format('d M Y') }} |
@endif@if($inquiry->guests)| **Guests** | {{ $inquiry->guests }} |
@endif

**Message:**

> {{ $inquiry->message }}

---

Reply quickly — guests often book the first property that responds!

<x-mail::button :url="route('owner.inquiries.show', $inquiry)">
View & Reply to Enquiry →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

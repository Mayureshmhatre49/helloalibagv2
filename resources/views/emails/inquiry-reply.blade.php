<x-mail::message>

# You've Received a Reply!

Hi **{{ $inquiry->name }}**,

The owner of **{{ $inquiry->listing->title }}** has replied to your enquiry on Hello Alibaug.

<x-mail::panel>
**Regarding:** {{ $inquiry->listing->title }}
@if($inquiry->check_in)**Dates:** {{ $inquiry->check_in->format('d M Y') }}@if($inquiry->check_out) – {{ $inquiry->check_out->format('d M Y') }}@endif@endif
</x-mail::panel>

**Their reply:**

> {{ $inquiry->owner_reply }}

---

If you'd like to proceed with your booking or have further questions, you can reply directly to this email or view the full listing.

<x-mail::button :url="route('listing.show', [$inquiry->listing->category->slug, $inquiry->listing->slug])">
View Listing →
</x-mail::button>

We hope you have a wonderful stay in Alibaug! 🌊

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

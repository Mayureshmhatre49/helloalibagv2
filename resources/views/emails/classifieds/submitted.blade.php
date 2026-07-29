<x-mail::message>

# 🔔 New Classified Awaiting Approval

Hi **Admin**,

A new marketplace item has been submitted and is pending your review.

<x-mail::panel>
**{{ $classified->title }}**
Category: {{ $classified->category?->name }}
Price: {{ $classified->price ? '₹' . number_format($classified->price) : 'Not specified' }}
Submitted by: {{ $classified->seller->name }} ({{ $classified->seller->email }})
Submitted at: {{ $classified->created_at->format('d M Y, h:i A') }}
</x-mail::panel>

<x-mail::button :url="route('admin.classifieds.index', ['status' => 'pending'])">
Review in Admin Panel →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

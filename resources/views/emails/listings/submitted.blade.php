<x-mail::message>

@if($isAdmin)
# 🔔 New Listing Awaiting Approval

Hi **Admin**,

A new listing has been submitted and is pending your review.

<x-mail::panel>
**{{ $listing->title }}**
Category: {{ $listing->category?->name }}
Submitted by: {{ $listing->creator->name }} ({{ $listing->creator->email }})
Submitted at: {{ $listing->created_at->format('d M Y, h:i A') }}
</x-mail::panel>

Please review this listing and approve or reject it from the admin panel.

<x-mail::button :url="route('admin.listings.index')">
Review in Admin Panel →
</x-mail::button>

@else
# Listing Submitted — We're Reviewing It!

Hi **{{ $listing->creator->name }}**,

Thank you for submitting your listing on **Hello Alibaug**! 🙌

We've received your submission and our team is now reviewing it.

<x-mail::panel>
**{{ $listing->title }}**
{{ $listing->category?->name }} · {{ $listing->area?->name ?? 'Alibaug' }}
</x-mail::panel>

**What happens next?**

| Step | Status |
|------|--------|
| Listing submitted | ✅ Done |
| Under review by our team | 🔄 In progress |
| Approval email sent to you | ⏳ Soon |
| Listing goes live | ⏳ Soon |

Our team typically reviews listings within **24–48 hours**. You'll receive another email once a decision has been made.

In the meantime, you can track your listing status from your dashboard.

<x-mail::button :url="route('owner.dashboard')">
Go to My Dashboard →
</x-mail::button>

@endif

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

<x-mail::message>

# Update on Your Listing

Hi **{{ $listing->creator->name }}**,

Thank you for submitting **"{{ $listing->title }}"** for review. After careful consideration, we're unable to approve it at this time.

---

**Reason for rejection:**

<x-mail::panel>
{{ $listing->rejection_reason ?? 'The listing did not meet our quality guidelines.' }}
</x-mail::panel>

**What you can do:**

Please review the feedback above and update your listing accordingly. Once you've made the necessary changes, you can resubmit it for approval — we'd love to have your business on Hello Alibaug!

Here are some common things we check:

- Clear, accurate title and description
- At least one good-quality photo
- Valid contact details and location

<x-mail::button :url="route('owner.listings.edit', $listing)" color="green">
Edit & Resubmit Listing →
</x-mail::button>

If you have any questions or believe this decision was made in error, please reply to this email — we're happy to help.

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

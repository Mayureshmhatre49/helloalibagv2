<x-mail::message>

# 🎉 Your listing is live!

Hi **{{ $listing->creator->name }}**,

Excellent news — your listing has been reviewed, approved, and is now **live on Hello Alibaug**!

<x-mail::panel>
**{{ $listing->title }}**
{{ $listing->category?->name }} · {{ $listing->area?->name ?? 'Alibaug' }}
</x-mail::panel>

Travellers browsing Hello Alibaug can now discover, save, and contact you directly through your listing page.

<x-mail::button :url="route('listing.show', [$listing->category->slug, $listing->slug])">
View Your Live Listing →
</x-mail::button>

---

**📌 A few tips to get more enquiries:**

- Add high-quality photos (at least 6–8 images)
- Keep your price and availability up to date
- Respond to enquiries within 24 hours to build trust

If you ever need to update your listing, you can do so anytime from your **[owner dashboard]({{ route('owner.dashboard') }})**.

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

<x-mail::message>

# Welcome to Hello Alibaug! 🌊

Hi **{{ $user->name }}**,

We're thrilled to have you join the **Hello Alibaug** community — your gateway to the finest stays, dining, and experiences along the Konkan coast.

<x-mail::panel>
Your account is all set up and ready to explore!
**Email:** {{ $user->email }}
</x-mail::panel>

**Here's what you can do:**

- 🔍 **Discover** handpicked villas, restaurants, and experiences in Alibaug
- 🗺️ **Explore the map** to find places near you
- ⭐ **Save your favourites** and come back anytime
- ✍️ **Leave reviews** and help the community

<x-mail::button :url="config('app.url')">
Start Exploring Alibaug →
</x-mail::button>

---

**Want to list your property or business?**

If you own a villa, restaurant, or offer experiences in the Alibaug area, we'd love to have you on the platform!

[List for Free →]({{ route('owner.onboarding') }})

---

If you have any questions, just reply to this email — we're always happy to help.

Warm regards,<br>
**The Hello Alibaug Team**<br>
*Discover · Stay · Eat*

</x-mail::message>

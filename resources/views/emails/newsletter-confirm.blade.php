<x-mail::message>

# Confirm Your Subscription 📬

Hi there,

Thank you for signing up for **Hello Alibaug Insights**! 

You're one click away from receiving:

- 🏖️ **Hidden gems** across Alibaug & the Konkan coast
- 🏡 **Exclusive villa deals** and seasonal offers
- 🍽️ **Food guides** — the best local restaurants and experiences
- 📅 **Events & festivals** happening near you

Please confirm your email address to activate your subscription.

<x-mail::button :url="route('newsletter.confirm', $subscriber->unsubscribe_token)">
Yes, Confirm My Subscription →
</x-mail::button>

---

*Didn't sign up for this? No worries — just ignore this email and you won't be added to our list.*

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

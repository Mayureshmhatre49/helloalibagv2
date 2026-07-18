@component('mail::message')
# Confirm Your Subscription

Thanks for signing up for Hello Alibaug Insights! Please confirm your email to start receiving our best travel guides, hidden gems, and exclusive villa deals.

@component('mail::button', ['url' => route('newsletter.confirm', $subscriber->unsubscribe_token)])
Confirm Subscription
@endcomponent

If you didn't sign up for this, you can safely ignore this email — you won't be subscribed unless you click the button above.

Thanks,
{{ config('app.name') }}
@endcomponent

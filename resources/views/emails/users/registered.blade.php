<x-mail::message>

# 🆕 New Registration on Hello Alibaug

Hi **Admin**,

A new account just signed up.

<x-mail::panel>
**{{ $user->name }}**
{{ $user->email }}
Account type: {{ $user->role?->name ?? 'User' }}
Registered at: {{ $user->created_at->format('d M Y, h:i A') }}
</x-mail::panel>

<x-mail::button :url="route('admin.users.index', ['search' => $user->email])">
View in Admin Panel →
</x-mail::button>

Warm regards,<br>
**The Hello Alibaug Team**

</x-mail::message>

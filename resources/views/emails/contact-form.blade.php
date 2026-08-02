<x-mail::message>

# 📬 New Contact Form Submission

A new message has been received through the Hello Alibaug contact form.

<x-mail::panel>
**From:** {{ $senderName }}
**Email:** {{ $senderEmail }}
**Subject:** {{ $contactSubject }}
</x-mail::panel>

**Message:**

> {!! nl2br(e($messageBody)) !!}

---

*Reply directly to this email to respond to {{ $senderName }}.*

**The Hello Alibaug Team**

</x-mail::message>

<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\NewsletterConfirmationMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        // Honeypot: bots fill hidden fields, humans don't
        if ($request->filled('website') || $request->filled('phone_verify')) {
            return redirect()->route('page.contact')
                ->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        try {
            Mail::to(config('mail.from.address'))->send(new ContactFormMail(
                senderName: $request->name,
                senderEmail: $request->email,
                subject: $request->subject,
                messageBody: $request->message,
            ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Contact form email failed to send: ' . $e->getMessage());
        }

        return redirect()->route('page.contact')
            ->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function emergency()
    {
        return view('pages.emergency');
    }

    public function localMarkets()
    {
        return view('pages.local-markets');
    }

    public function howToReach()
    {
        return view('pages.how-to-reach');
    }

    public function ferrySchedule()
    {
        return view('pages.ferry-schedule');
    }

    public function beaches()
    {
        return view('pages.beaches');
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->input('email')],
            ['unsubscribe_token' => Str::random(64)],
        );

        if ($subscriber->confirmed_at) {
            Cookie::queue('newsletter_subscribed', $subscriber->unsubscribe_token, 60 * 24 * 365);

            return back()->with('newsletter_success', 'You\'re already subscribed!');
        }

        try {
            Mail::to($subscriber->email)->send(new NewsletterConfirmationMail($subscriber));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Newsletter confirmation email failed to send: ' . $e->getMessage());
        }

        return back()->with('newsletter_success', 'Almost there! Check your inbox to confirm your subscription.');
    }

    public function newsletterConfirm(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->firstOrFail();

        if (!$subscriber->confirmed_at) {
            $subscriber->update(['confirmed_at' => now()]);
        }

        Cookie::queue('newsletter_subscribed', $subscriber->unsubscribe_token, 60 * 24 * 365);

        return view('pages.newsletter-confirmed');
    }

    public function newsletterUnsubscribe(string $token)
    {
        NewsletterSubscriber::where('unsubscribe_token', $token)->delete();

        Cookie::queue(Cookie::forget('newsletter_subscribed'));

        return view('pages.newsletter-unsubscribed');
    }
}

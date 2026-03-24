<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        Mail::to(config('mail.from.address'))->send(new ContactFormMail(
            senderName: $request->name,
            senderEmail: $request->email,
            subject: $request->subject,
            messageBody: $request->message,
        ));

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

    public function newsletterSubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->input('email')],
            ['unsubscribe_token' => Str::random(64)],
        );

        return back()->with('newsletter_success', 'You\'re subscribed! Welcome to the Hello Alibaug community.');
    }
}

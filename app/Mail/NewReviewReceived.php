<?php

namespace App\Mail;

use App\Models\Listing;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReviewReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Listing $listing, public Review $review) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Review Received for ' . $this->listing->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.listings.review',
        );
    }
}

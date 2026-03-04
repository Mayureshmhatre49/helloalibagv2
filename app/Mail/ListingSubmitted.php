<?php

namespace App\Mail;

use App\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Listing $listing,
        public bool $isAdmin = false
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isAdmin 
                ? 'Action Required: New Listing Submitted - ' . $this->listing->title
                : 'Listing Submitted Successfully - ' . $this->listing->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.listings.submitted',
        );
    }
}

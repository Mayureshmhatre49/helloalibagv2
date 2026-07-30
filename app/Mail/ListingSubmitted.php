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
        public bool $isAdmin = false,
        public bool $isResubmission = false
    ) {}

    public function envelope(): Envelope
    {
        if ($this->isAdmin) {
            $subject = $this->isResubmission
                ? 'Action Required: Listing Resubmitted - ' . $this->listing->title
                : 'Action Required: New Listing Submitted - ' . $this->listing->title;
        } else {
            $subject = 'Listing Submitted Successfully - ' . $this->listing->title;
        }

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.listings.submitted',
        );
    }
}

<?php

namespace App\Mail;

use App\Models\Classified;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClassifiedSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Classified $classified) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: New Classified Submitted - ' . $this->classified->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.classifieds.submitted',
        );
    }
}

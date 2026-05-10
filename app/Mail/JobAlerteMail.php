<?php

namespace App\Mail;

use App\Models\Offre;
use App\Models\Alerte;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobAlerteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $offre;
    public $alerte;

    /**
     * Create a new message instance.
     */
    public function __construct(Offre $offre, Alerte $alerte)
    {
        $this->offre = $offre;
        $this->alerte = $alerte;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔔 Nouvelle offre : {$this->offre->titre} (Alerte ACPE)",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-alerte',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

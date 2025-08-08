<?php

namespace App\Mail;

use App\Models\Conductor;
use App\Models\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaRegistrado extends Mailable
{
    use Queueable, SerializesModels;

    public $conductor;
    public $evento;

    /**
     * Create a new message instance.
     */
    public function __construct(Conductor $conductor , Evento $evento)
    {
        $this->conductor = $conductor;
        $this->evento = $evento;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva reserva registrada',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registrado_reserva',
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

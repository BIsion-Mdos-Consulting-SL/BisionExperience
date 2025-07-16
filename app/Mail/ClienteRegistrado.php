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

class ClienteRegistrado extends Mailable
{
    use Queueable, SerializesModels;

    //Hacemos publico los objetos.
    public $conductor;
    public $evento;

    /**
     * Create a new message instance. Se guardan los campos pasamos por parametro y los modelos.
     */
    public function __construct(Conductor $conductor , Evento $evento)
    {
        $this->conductor = $conductor;
        $this->evento = $evento;
    }

    /**
     * Get the message envelope.Muestra el mensaje de titulo de correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo invitado registrado',
        );
    }

    /**
     * Get the message content definition.Muestra la informacion con la vista del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registrado',
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

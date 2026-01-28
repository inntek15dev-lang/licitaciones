<?php

namespace App\Mail;

use App\Models\Licitacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicitacionObservadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Licitacion $licitacion;
    public string $observacion;
    public string $revisorNombre;

    /**
     * Create a new message instance.
     */
    public function __construct(Licitacion $licitacion, string $observacion, string $revisorNombre)
    {
        $this->licitacion = $licitacion;
        $this->observacion = $observacion;
        $this->revisorNombre = $revisorNombre;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Licitación Observada: ' . $this->licitacion->titulo,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.licitacion-observada',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

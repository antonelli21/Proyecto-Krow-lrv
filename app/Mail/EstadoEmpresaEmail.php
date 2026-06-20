<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Empresa;

class EstadoEmpresaEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Empresa $empresa;
    public string $estado;

    /**
     * Create a new message instance.
     */
    public function __construct(Empresa $empresa, string $estado)
    {
        $this->empresa = $empresa;
        $this->estado = $estado;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $asunto = $this->estado === 'aprobada' 
            ? 'KROW - Tu cuenta de empresa ha sido aprobada'
            : 'KROW - Actualización sobre tu cuenta de empresa';

        return new Envelope(
            subject: $asunto,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.estado-empresa',
        );
    }
}

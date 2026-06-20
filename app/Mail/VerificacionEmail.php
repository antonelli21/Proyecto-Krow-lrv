<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificacionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $codigo;
    public string $userName;

    // El controlador nos pasa el código y el nombre del usuario por acá
    public function __construct(string $user, string $codigo)
    {
        $this->codigo = $codigo;
        $this->userName = $user;
    }

    // El asunto que va a aparecer en el Gmail de la persona
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KROW - Código de Verificación de Email',
        );
    }

    // Tu HTML oscuro guardado en resources/views/emails/verificacion.blade.php
    public function content(): Content
    {
        return new Content(
            view: 'emails.verificacion',
        );
    }
}

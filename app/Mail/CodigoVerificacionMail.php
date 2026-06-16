<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CodigoVerificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declaramos las variables públicas para que tu Blade las pueda leer
    public string $codigo;
    public string $userName;

    // Recibimos el código y el nombre del usuario desde el controlador
    public function __construct(string $userName, string $codigo)
    {
        $this->codigo = $codigo;
        $this->userName = $userName;
    }

    // El asunto que vas a ver en tu bandeja de entrada de Gmail
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔑 Tu código de verificación de KROW',
        );
    }

    // Le decimos a Laravel que use tu plantilla oscura de KROW
    public function content(): Content
    {
        return new Content(
            view: 'emails.verificacion',
        );
    }
}

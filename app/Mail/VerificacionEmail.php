<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * VerificacionEmail — Mailable para enviar el código de verificación.
 * Se envía cuando un usuario se registra para verificar que el email sea válido.
 * Contiene un código de 6 dígitos que expira en 30 minutos.
 */
class VerificacionEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * El usuario que se registró.
     */
    public User $user;

    /**
     * El código de verificación de 6 dígitos.
     */
    public int $codigo;

    /**
     * Crear una nueva instancia del mailable.
     *
     * @param  User  $user    El usuario registrado
     * @param  int   $codigo  El código de verificación
     */
    public function __construct(User $user, int $codigo)
    {
        $this->user   = $user;
        $this->codigo = $codigo;
    }

    /**
     * Definir el sobre (subject) del email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KROW - Código de Verificación de Email',
        );
    }

    /**
     * Definir el contenido del email.
     * Usa la vista 'emails.verificacion' con los datos del usuario y código.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verificacion',
            with: [
                'userName' => $this->user->name,
                'codigo'   => $this->codigo,
            ],
        );
    }
}

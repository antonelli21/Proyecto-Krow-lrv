<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Email - KROW</title>
</head>
<body style="margin:0; padding:0; background-color:#0f0f1a; font-family: 'Inter', system-ui, sans-serif;">
    {{-- ═══════════════════════════════════════════════════════════
         Template del email de verificación.
         Muestra el código de 6 dígitos con un diseño profesional.
         El código expira en 30 minutos.
    ═══════════════════════════════════════════════════════════ --}}

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f1a; padding: 40px 0;">
        <tr>
            <td align="center">
                {{-- Contenedor principal del email --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                       style="background-color:#1a1a2e; border-radius:16px; overflow:hidden; box-shadow: 0 4px 30px rgba(0,0,0,0.3);">

                    {{-- Header con degradado --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #6c5ce7, #a29bfe); padding: 40px 30px; text-align:center;">
                            <h1 style="color:#fff; margin:0; font-size:28px; letter-spacing:2px;">KROW</h1>
                            <p style="color:rgba(255,255,255,0.85); margin:8px 0 0; font-size:14px;">Banco de Trabajo</p>
                        </td>
                    </tr>

                    {{-- Cuerpo del email --}}
                    <tr>
                        <td style="padding: 40px 30px;">
                            {{-- Saludo al usuario --}}
                            <h2 style="color:#e0e0e0; margin:0 0 16px; font-size:22px;">
                                ¡Hola{{ $userName ? ', ' . $userName : '' }}! 👋
                            </h2>

                            {{-- Mensaje explicativo --}}
                            <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                Gracias por registrarte en <strong style="color:#a29bfe;">KROW</strong>.
                                Para completar tu registro, ingresá el siguiente código de verificación:
                            </p>

                            {{-- Código de verificación destacado --}}
                            <div style="background-color:#16213e; border: 2px solid #6c5ce7; border-radius:12px; padding:25px; text-align:center; margin:0 0 30px;">
                                <p style="color:#b0b0b0; font-size:13px; margin:0 0 10px; text-transform:uppercase; letter-spacing:1px;">
                                    Tu código de verificación
                                </p>
                                <p style="color:#a29bfe; font-size:42px; font-weight:700; letter-spacing:12px; margin:0; font-family: 'Sora', system-ui, sans-serif;">
                                    {{ $codigo }}
                                </p>
                            </div>

                            {{-- Advertencia de expiración --}}
                            <p style="color:#b0b0b0; font-size:14px; line-height:1.6; margin:0 0 10px;">
                                ⏱️ Este código expira en <strong style="color:#e0e0e0;">30 minutos</strong>.
                            </p>

                            {{-- Advertencia de seguridad --}}
                            <p style="color:#888; font-size:13px; line-height:1.5; margin:0;">
                                Si no creaste una cuenta en KROW, podés ignorar este email.
                                No compartas este código con nadie.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer del email --}}
                    <tr>
                        <td style="background-color:#16213e; padding:20px 30px; text-align:center; border-top: 1px solid rgba(108,92,231,0.3);">
                            <p style="color:#666; font-size:12px; margin:0;">
                                &copy; {{ date('Y') }} KROW — Banco de Trabajo. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

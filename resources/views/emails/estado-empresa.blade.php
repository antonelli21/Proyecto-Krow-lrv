<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Cuenta - KROW</title>
</head>
<body style="margin:0; padding:0; background-color:#0f0f1a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#0f0f1a; padding: 40px 0;">
        <tr>
            <td align="center">
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
                            {{-- Saludo a la empresa --}}
                            <h2 style="color:#e0e0e0; margin:0 0 16px; font-size:22px;">
                                ¡Hola, {{ $empresa->nombre_empresa }}! 👋
                            </h2>

                            @if($estado === 'aprobada')
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    ¡Excelentes noticias! Tu cuenta de empresa ha sido <strong style="color:#00b894;">aprobada</strong> exitosamente en <strong style="color:#a29bfe;">KROW</strong>.
                                </p>
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    Ya podés iniciar sesión para completar tu perfil, publicar ofertas de trabajo y empezar a conectar con los mejores talentos de la universidad.
                                </p>
                                <div style="text-align: center; margin-bottom: 30px;">
                                    <a href="{{ route('login') }}" style="background-color: #6c5ce7; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; display: inline-block;">Iniciar Sesión</a>
                                </div>
                            @elseif($estado === 'rechazada')
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    Te informamos que, tras revisar tu solicitud, tu cuenta de empresa ha sido <strong style="color:#d63031;">rechazada</strong> en <strong style="color:#a29bfe;">KROW</strong>.
                                </p>
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    Si creés que se trata de un error o deseás recibir más información, por favor contactate con nuestro equipo de soporte.
                                </p>
                            @elseif($estado === 'suspendida')
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    Te informamos que tu cuenta de empresa en <strong style="color:#a29bfe;">KROW</strong> ha sido <strong style="color:#fdcb6e;">suspendida</strong> temporalmente.
                                </p>
                                <p style="color:#b0b0b0; font-size:15px; line-height:1.6; margin:0 0 30px;">
                                    Durante este período, no podrás publicar nuevas ofertas ni acceder a la plataforma. Por favor, contactate con nuestro equipo de soporte para resolver esta situación.
                                </p>
                            @endif

                            <p style="color:#888; font-size:13px; line-height:1.5; margin:0; margin-top:20px;">
                                Saludos,<br>
                                El equipo de KROW.
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

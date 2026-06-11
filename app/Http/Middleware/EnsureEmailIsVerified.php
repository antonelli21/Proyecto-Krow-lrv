<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureEmailIsVerified — Middleware que verifica que el email del usuario
 * esté verificado antes de permitir el acceso a rutas protegidas.
 * Si el email no fue verificado, redirige al formulario de verificación.
 */
class EnsureEmailIsVerified
{
    /**
     * Manejar la solicitud entrante.
     * Verifica que el campo email_verified_at no sea null.
     *
     * @param  Request  $request  La solicitud HTTP entrante
     * @param  Closure  $next     El siguiente middleware o controlador
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado pero no verificó su email
        if ($request->user() && is_null($request->user()->email_verified_at)) {
            // Guardar el id en sesión para el flujo de verificación
            session(['verificacion_user_id' => $request->user()->id]);

            // Redirigir a la página de verificación de email
            return redirect()->route('verificacion.mostrar')
                ->with('warning', 'Necesitás verificar tu email para acceder.');
        }

        return $next($request);
    }
}

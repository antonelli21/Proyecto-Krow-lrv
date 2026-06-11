<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectIfAuthenticated — Middleware que redirige a los usuarios
 * que ya están autenticados cuando intentan acceder a rutas de invitados
 * (como login o registro). Los manda a su panel correspondiente.
 *
 * Es el opuesto de 'auth': 'auth' exige estar logueado, 
 * 'guest' exige NO estar logueado.
 */
class RedirectIfAuthenticated
{
    /**
     * Manejar la solicitud entrante.
     * Si el usuario ya está logueado, lo redirige a su panel.
     *
     * @param  Request  $request  La solicitud HTTP entrante
     * @param  Closure  $next     El siguiente middleware o controlador
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario ya está autenticado, redirigir a su panel
        if ($request->user()) {
            // Redirigir según el rol del usuario
            return match ($request->user()->rol) {
                'estudiante' => redirect()->route('estudiante.home'),
                'empresa'    => redirect()->route('empresa.home'),
                'admin'      => redirect()->route('admin.home'),
                default      => redirect()->route('inicio'),
            };
        }

        return $next($request);
    }
}

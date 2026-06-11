<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckRole — Middleware que verifica que el usuario autenticado
 * tenga uno de los roles permitidos para acceder a la ruta.
 * Se usa para proteger las rutas de estudiante, empresa y admin.
 *
 * Uso en rutas:
 *   ->middleware('role:estudiante')
 *   ->middleware('role:empresa')
 *   ->middleware('role:admin')
 *   ->middleware('role:admin,empresa')  // Permitir múltiples roles
 */
class CheckRole
{
    /**
     * Manejar la solicitud entrante.
     * Verifica que el rol del usuario esté en la lista de roles permitidos.
     *
     * @param  Request  $request  La solicitud HTTP entrante
     * @param  Closure  $next     El siguiente middleware o controlador
     * @param  string   ...$roles Los roles permitidos (separados por coma)
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder.');
        }

        // Verificar que el rol del usuario esté dentro de los permitidos
        if (!in_array($request->user()->rol, $roles)) {
            // Si no tiene permiso, devolver error 403 (Forbidden)
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}

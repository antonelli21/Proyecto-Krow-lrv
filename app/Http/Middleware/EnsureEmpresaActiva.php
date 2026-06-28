<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEmpresaActiva
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->rol === 'empresa') {
            $empresa = $user->empresa;

            if (!$empresa) {
                abort(403, 'Empresa no registrada.');
            }

            // Suspendida o rechazada → logout directo
            if (in_array($empresa->estado, ['suspendida', 'rechazada'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $mensaje = match ($empresa->estado) {
                    'suspendida' => 'Tu cuenta de empresa se encuentra temporalmente suspendida.',
                    'rechazada'  => 'Tu empresa fue rechazada y no puede acceder al sistema.',
                };

                return redirect()->route('login')->with('error', $mensaje);
            }

            // Pendiente → solo puede acceder a mensajes y notificaciones
            if ($empresa->estado === 'pendiente') {
                $rutasPermitidas = [
                    'empresa.mensajes',
                    'mensajes',
                    'notificaciones.historial',
                    'notificaciones.api.resumen',
                    'notificaciones.api.contador',
                    'notificaciones.api.marcar-leida',
                    'notificaciones.api.marcar-todas',
                    'logout',
                ];

                if (!in_array($request->route()->getName(), $rutasPermitidas)) {
                    return redirect()->route('empresa.mensajes')
                        ->with('error', 'Tu empresa está pendiente de aprobación. Solo podés acceder a mensajes para contactar al administrador.');
                }
            }
        }

        return $next($request);
    }
}
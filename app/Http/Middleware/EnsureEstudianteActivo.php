<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureEstudianteActivo
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Solo aplica a estudiantes
        if ($user && $user->rol === 'estudiante') {
            $estudiante = $user->estudiante; // Relación user -> estudiante

            if (!$estudiante) {
                abort(403, 'Perfil de estudiante no encontrado.');
            }

            // Si está suspendido, lo sacamos del sistema
            if ($estudiante->estado === 'suspendido') {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Tu cuenta de estudiante ha sido suspendida temporalmente por el administrador.');
            }
        }

        return $next($request);
    }
}
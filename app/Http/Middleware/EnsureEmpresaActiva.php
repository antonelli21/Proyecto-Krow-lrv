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

            if ($empresa->estado !== 'aprobada') {
                $estadoActual = $empresa->estado;

                // CAMBIÁ EL auth()->logout() POR ESTO:
                Auth::logout(); 

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $mensaje = match ($estadoActual) {
                    'pendiente'  => 'Tu empresa está pendiente de aprobación por el administrador.',
                    'suspendida' => 'Tu cuenta de empresa se encuentra temporalmente suspendida.',
                    'rechazada'  => 'Tu empresa fue rechazada y no puede acceder al sistema.',
                    default      => 'No tenés acceso autorizado.',
                };

                return redirect()->route('login')->with('error', $mensaje);
            }
        }

        return $next($request);
    }
}
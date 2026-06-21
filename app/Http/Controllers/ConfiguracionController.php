<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConfiguracionController extends Controller
{
    /**
     * Mostrar la vista de configuración.
     */
    public function index()
    {
        return view('configuracion');
    }

    /**
     * Cambiar contraseña del usuario autenticado.
     */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => ['required'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_actual.required' => 'Ingresá tu contraseña actual.',
            'password.required'        => 'La nueva contraseña es obligatoria.',
            'password.min'             => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'       => 'Las contraseñas no coinciden.',
        ]);

        // Verificar contraseña actual
        if (! Hash::check($request->password_actual, Auth::user()->password)) {
            return back()
                ->withErrors(['password_actual' => 'La contraseña actual es incorrecta.'])
                ->withInput();
        }

        // Actualizar sin pasar por Eloquent
        DB::table('users')
            ->where('id', Auth::id())
            ->update(['password' => Hash::make($request->password)]);

        return back()->with('password_ok', true);
    }

    /**
     * Cerrar todas las sesiones activas del usuario (excepto la actual).
     * Elimina todos los registros de la tabla sessions para este usuario.
     */
    public function logoutAll(Request $request)
    {
        $userId = Auth::id();
        $sessionActual = $request->session()->getId();

        // Eliminar todas las sesiones del usuario excepto la actual
        DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $sessionActual)
            ->delete();

        return back()->with('logout_all_ok', true);
    }
}

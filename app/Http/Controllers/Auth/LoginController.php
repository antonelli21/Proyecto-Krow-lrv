<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController — Maneja el inicio de sesión de usuarios.
 * Valida las credenciales (email + contraseña) y redirige
 * al panel correspondiente según el rol del usuario.
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de login.
     * Valida email y contraseña, luego usa Auth::attempt
     * para verificar las credenciales contra la base de datos.
     */
    public function login(Request $request)
    {
        // Validación de los campos del formulario de login
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificar que el email esté verificado antes de permitir login
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && !is_null($user->email_verification_code)) {
            session(['verificacion_user_id' => $user->id]);

            return redirect()
                ->route('verificacion.mostrar')
                ->with('success', 'Antes de iniciar sesión, verificá tu email.');
        } else {    
            // Obtener el valor del checkbox 'remember' (devuelve true si está marcado, false si no)
        $remember = $request->has('remember') || $request->boolean('remember');

        // Intentar autenticar con las credenciales y opción "recordarme"
        if (Auth::attempt($credentials, $remember)) {
            // Regenerar la sesión para prevenir session fixation
            $request->session()->regenerate();

            // Redirigir según el rol del usuario autenticado
            return match (Auth::user()->rol) {
                'estudiante' => redirect()->route('estudiante.home'),
                'empresa'    => redirect()->route('empresa.home'),
                'admin'      => redirect()->route('admin.home'),
                default      => redirect()->route('inicio'),
            };
        }

        // Si las credenciales son incorrectas, volver con error
        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
            ->withInput($request->only('email'));
    }
     }
    /**
     * Cierra la sesión del usuario autenticado.
     * Invalida la sesión y regenera el token CSRF.
     */
    public function logout(Request $request)
    {
        // Cerrar la sesión del usuario
        Auth::logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF para seguridad
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}

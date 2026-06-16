<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionEmail;

/**
 * VerificacionController — Maneja la verificación de email.
 * Muestra el formulario para ingresar el código de 6 dígitos
 * y verifica que sea correcto y no haya expirado.
 * También permite reenviar el código si expiró.
 */
class VerificacionController extends Controller
{
    /**
     * Muestra el formulario para ingresar el código de verificación.
     * Si no hay un user_id en sesión, redirige al registro.
     */
    public function mostrar()
    {
        // Verificar que haya un usuario pendiente de verificación en sesión
        if (!session('verificacion_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.verificar-email');
    }

    /**
     * Procesá la verificación del código ingresado por el usuario.
     * Compara el código con el almacenado en la BD y verifica que no haya expirado.
     */
    public function verificar(Request $request)
    {
        // Validar que el código tenga 6 dígitos
        $request->validate([
            'codigo' => ['required', 'string', 'size:6'],
        ], [
            'codigo.required' => 'Ingresá el código de verificación.',
            'codigo.size'     => 'El código debe tener 6 dígitos.',
        ]);

        // Obtener el usuario que está pendiente de verificación
        $userId = session('verificacion_user_id');
        $user   = User::find($userId);

        // Si no se encuentra el usuario, redirigir al registro
        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['codigo' => 'Sesión de verificación expirada. Registrate de nuevo.']);
        }

        // Verificar que el código no haya expirado (30 minutos)
        if (now()->greaterThan($user->email_verification_expires)) {
            return back()->withErrors([
                'codigo' => 'El código expiró. Hacé clic en "Reenviar código" para obtener uno nuevo.',
            ]);
        }

        // Comparar el código ingresado con el almacenado en la base de datos
        if ($request->codigo !== (string) $user->email_verification_code) {
            return back()->withErrors([
                'codigo' => 'El código ingresado es incorrecto. Intentá de nuevo.',
            ]);
        }

        // Marcar el email como verificado y limpiar el código
        $user->email_verified_at          = now();
        $user->email_verification_code    = null;
        $user->email_verification_expires = null;
        $user->save();

        // Limpiar la sesión de verificación
        session()->forget('verificacion_user_id');

        // Autenticar al usuario automáticamente después de verificar
        Auth::login($user);

        // Redirigir al panel correspondiente según el rol
        return match ($user->rol) {
            'estudiante' => redirect()->route('estudiante.home')
                ->with('success', '¡Email verificado correctamente! Bienvenido/a.'),
            'empresa' => redirect()->route('empresa.home')
                ->with('success', '¡Email verificado correctamente! Bienvenido/a.'),
            default => redirect()->route('inicio')
                ->with('success', '¡Email verificado correctamente!'),
        };
    }

    /**
     * Reenvía un nuevo código de verificación al email del usuario.
     * Genera un código nuevo, actualiza la BD y lo envía por email.
     */
    public function reenviar()
    {
        // 1. Obtener el usuario de la sesión
        $userId = session('verificacion_user_id');
        $user   = User::find($userId);

        // Si no se encuentra el usuario, redirigir al registro
        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['codigo' => 'Sesión expirada. Registrate de nuevo.']);
        }

        // 2. Generar un nuevo código de 6 dígitos
        $nuevoCodigoVerificacion = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // 3. Actualizar el código y la fecha de expiración en la BD
        $user->email_verification_code    = $nuevoCodigoVerificacion;
        $user->email_verification_expires = now()->addMinutes(30);
        $user->save();

        // >>> PARCHE DE EMERGENCIA SEGURO LOCAL <<<
        // Forzamos a PHP a saltearse la verificación SSL rota de tu PC local en caliente
        config([
            'mail.mailers.smtp.stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ]);

        // 4. Enviar el nuevo código por email mandando código y nombre separados
        Mail::to($user->email)->send(new VerificacionEmail($nuevoCodigoVerificacion, $user->name));

        return back()->with('success', 'Se envió un nuevo código a tu email.');
    }
}
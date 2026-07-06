<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionEmail;


class VerificacionController extends Controller
{

    public function mostrar()
    {

        if (!session('verificacion_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.verificar-email');
    }


    public function verificar(Request $request)
    {

        $request->validate([
            'codigo' => ['required', 'string', 'size:6'],
        ], [
            'codigo.required' => 'Ingresá el código de verificación.',
            'codigo.size'     => 'El código debe tener 6 dígitos.',
        ]);


        $userId = session('verificacion_user_id');
        $user   = User::find($userId);


        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['codigo' => 'Sesión de verificación expirada. Registrate de nuevo.']);
        }


        if (now()->greaterThan($user->email_verification_expires)) {
            return back()->withErrors([
                'codigo' => 'El código expiró. Hacé clic en "Reenviar código" para obtener uno nuevo.',
            ]);
        }


        if ($request->codigo !== (string) $user->email_verification_code) {
            return back()->withErrors([
                'codigo' => 'El código ingresado es incorrecto. Intentá de nuevo.',
            ]);
        }


        $user->email_verified_at          = now();
        $user->email_verification_code    = null;
        $user->email_verification_expires = null;
        $user->save();


        session()->forget('verificacion_user_id');


        if ($user->rol === 'empresa') {
            $empresa = $user->empresa;
            if ($empresa && $empresa->estado === 'aprobada') {
                Auth::login($user);
                return redirect()->route('empresa.home')->with('success', '¡Email verificado correctamente! Bienvenido/a.');
            } else {
                return redirect()->route('registro.pendiente');
            }
        }

        Auth::login($user);

        return match ($user->rol) {
            'estudiante' => redirect()->route('estudiante.home')
                ->with('success', '¡Email verificado correctamente! Bienvenido/a.'),
            default => redirect()->route('inicio')
                ->with('success', '¡Email verificado correctamente!'),
        };
    }


    public function reenviar()
    {

        $userId = session('verificacion_user_id');
        $user   = User::find($userId);


        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['codigo' => 'Sesión expirada. Registrate de nuevo.']);
        }


        $nuevoCodigoVerificacion = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);


        $user->email_verification_code    = $nuevoCodigoVerificacion;
        $user->email_verification_expires = now()->addMinutes(30);
        $user->save();


        config([
            'mail.mailers.smtp.stream' => [
                'ssl' => [
                    'allow_self_signed' => true,
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ],
        ]);


        Mail::to($user->email)->send(new VerificacionEmail($nuevoCodigoVerificacion, $user->name));

        return back()->with('success', 'Se envió un nuevo código a tu email.');
    }
}
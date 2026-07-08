<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && !is_null($user->email_verification_code)) {
            session(['verificacion_user_id' => $user->id]);

            return redirect()
                ->route('verificacion.mostrar')
                ->with('success', 'Antes de iniciar sesión, verificá tu email.');
        } else {
            $remember = $request->has('remember') || $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                DB::table('users')
                    ->where('id', Auth::id())
                    ->update(['last_login_at' => now()]);

                return match (Auth::user()->rol) {
                    'estudiante' => redirect()->route('estudiante.home'),
                    'empresa'    => redirect()->route('empresa.home'),
                    'admin'      => redirect()->route('admin.home'),
                    default      => redirect()->route('inicio'),
                };
            }

            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->withInput($request->only('email'))
                ->with('error', 'Las credenciales no coinciden con nuestros registros.');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}

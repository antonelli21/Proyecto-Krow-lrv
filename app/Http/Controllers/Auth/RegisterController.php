<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Empresa;
use App\Models\Carrera;
use App\Models\Provincia;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionEmail;
use App\Helpers\CuitValidator;


class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        $carreras   = Carrera::orderBy('nombre')->get();
        $provincias = Provincia::orderBy('nombre')->get();

        return view('auth.registro', compact('carreras', 'provincias'));
    }


    public function registerEstudiante(Request $request)
    {
        $rules = [
            'nombre'     => ['required', 'string', 'min:2', 'max:50'],
            'apellido'   => ['required', 'string', 'min:2', 'max:50'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'telefono'   => ['nullable', 'string', 'max:15'],
            'nacimiento' => ['nullable', 'date', 'before:today'],
            'carrera'    => ['required', 'string', 'exists:carrera,nombre'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
        ];

        // ╔══════════════════════════════════════════════════════════════╗
        // ║ VALIDACIÓN DE EMAIL INSTITUCIONAL (COMENTADA POR AHORA)    ║
        // ║ Descomentar cuando se quiera restringir solo a @alumnos... ║
        // ╚══════════════════════════════════════════════════════════════╝
        // $rules['email'][] = function ($attribute, $value, $fail) {
        //     $dominioPermitido = '@alumnos.utn.frh.edu.ar';
        //     if (!str_ends_with(strtolower($value), $dominioPermitido)) {
        //         $fail('El email debe ser institucional (' . $dominioPermitido . ').');
        //     }
        // };

        $validated = $request->validate($rules, [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Ingresa un email válido.',
            'email.unique'       => 'Este email ya está registrado.',
            'carrera.required'   => 'Seleccioná una carrera.',
            'carrera.exists'     => 'La carrera seleccionada no es válida.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $codigoVerificacion = random_int(100000, 999999);

        $user = User::create([
            'name'                       => $validated['nombre'] . ' ' . $validated['apellido'],
            'email'                      => $validated['email'],
            'password'                   => Hash::make($validated['password']),
            'rol'                        => 'estudiante',
            'email_verification_code'    => $codigoVerificacion,
            'email_verification_expires' => now()->addMinutes(30),
        ]);

        $carrera = Carrera::firstOrCreate(['nombre' => $validated['carrera']]);

        Estudiante::create([
            'id_usuario'       => $user->id,
            'nombre'           => $validated['nombre'],
            'apellido'         => $validated['apellido'],
            'dni'              => $request->input('dni', null),
            'legajo'           => $request->input('legajo', 'PENDIENTE-' . $user->id),
            'fecha_nacimiento' => $validated['nacimiento'] ?? null,
            'telefono'         => $validated['telefono'] ?? null,
            'id_carrera'       => $carrera->id_carrera,
        ]);

        Mail::to($user->email)->send(new VerificacionEmail($user->name, $codigoVerificacion));

        session(['verificacion_user_id' => $user->id]);

        return redirect()->route('verificacion.mostrar')
            ->with('success', 'Cuenta creada. Revisá tu email para obtener el código de verificación.');
    }


    public function registerEmpresa(Request $request)
    {
        $validated = $request->validate([
            'nombre_empresa' => ['required', 'string', 'max:100'],
            'razon_social'   => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'cuit'           => ['required', 'string'],
            'telefono'       => ['required', 'string', 'max:20'],
            'id_provincia'   => ['nullable', 'integer', 'exists:provincia,id_provincia'],
            'id_localidad'   => ['nullable', 'integer', 'exists:localidad,id_localidad'],
            'password'       => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nombre_empresa.required' => 'El nombre de la empresa es obligatorio.',
            'razon_social.required'   => 'La razón social es obligatoria.',
            'email.required'          => 'El email es obligatorio.',
            'email.email'             => 'Ingresa un email válido.',
            'email.unique'            => 'Este email ya está registrado.',
            'cuit.required'           => 'El CUIT es obligatorio.',
            'telefono.required'       => 'El teléfono es obligatorio.',
            'password.required'       => 'La contraseña es obligatoria.',
            'password.min'            => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed'      => 'Las contraseñas no coinciden.',
        ]);

        $cuitLimpio = preg_replace('/[^0-9]/', '', $validated['cuit']);
        if (!CuitValidator::validar($cuitLimpio)) {
            return back()
                ->withErrors(['cuit' => 'El CUIT ingresado no es válido. Verificá el número.'])
                ->withInput();
        }

        $codigoVerificacion = random_int(100000, 999999);

        $user = User::create([
            'name'                       => $validated['nombre_empresa'],
            'email'                      => $validated['email'],
            'password'                   => Hash::make($validated['password']),
            'rol'                        => 'empresa',
            'email_verification_code'    => $codigoVerificacion,
            'email_verification_expires' => now()->addMinutes(30),
        ]);

        $empresa = Empresa::create([
            'id_usuario'          => $user->id,
            'nombre_empresa'      => $validated['nombre_empresa'],
            'razon_social'        => $validated['razon_social'],
            'cuit'                => (int) $cuitLimpio,
            'rubro'               => $request->input('rubro', 'General'),
            'telefono'            => $validated['telefono'],
            'email_contacto'      => $validated['email'],
            'id_provincia'        => $validated['id_provincia'] ?? null,
            'id_localidad'        => $validated['id_localidad'] ?? null,
            'representante'       => $request->input('representante', $validated['nombre_empresa']),
            'email_representante' => $validated['email'],
        ]);

        // ── Notificar a todos los admins ──────────────────────
        \App\Models\User::where('rol', 'admin')->each(function ($admin) use ($empresa, $validated) {
            \DB::table('notificaciones')->insert([
                'id_usuario'  => $admin->id,
                'titulo'      => 'Nueva empresa registrada',
                'mensaje'     => "\"{$validated['nombre_empresa']}\" se registró y espera aprobación.",
                'url'         => route('admin.empresas'),
                'tipo'        => 'warning',
                'leida'       => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        });

        Mail::to($user->email)->send(new VerificacionEmail($user->name, $codigoVerificacion));

        session(['verificacion_user_id' => $user->id]);

        return redirect()->route('verificacion.mostrar')
            ->with('success', 'Cuenta creada. Revisá tu email para obtener el código de verificación.');
    }
}

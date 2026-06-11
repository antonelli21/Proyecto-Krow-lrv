<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificacionEmail;
use App\Helpers\CuitValidator;

/**
 * RegisterController — Maneja el registro de usuarios nuevos.
 * Soporta dos tipos de registro: Estudiante y Empresa.
 * Envía un código de verificación por email después del registro.
 * Para empresas, valida el CUIT antes de crear la cuenta.
 */
class RegisterController extends Controller
{
    /**
     * Muestra el formulario de registro (con tabs candidato/empresa).
     */
    public function showRegistrationForm()
    {
        return view('auth.registro');
    }

    /**
     * Procesa el registro de un ESTUDIANTE (candidato).
     * Valida los datos, crea el usuario y el perfil de estudiante,
     * genera un código de verificación y lo envía por email.
     */
    public function registerEstudiante(Request $request)
    {
        // Reglas de validación para el formulario de estudiante
        $rules = [
            'nombre'       => ['required', 'string', 'min:2', 'max:50'],
            'apellido'     => ['required', 'string', 'min:2', 'max:50'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'telefono'     => ['nullable', 'string', 'max:15'],
            'nacimiento'   => ['nullable', 'date', 'before:today'],
            'carrera'      => ['required', 'string'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
        ];

        // ╔══════════════════════════════════════════════════════════════╗
        // ║ VALIDACIÓN DE EMAIL INSTITUCIONAL (COMENTADA POR AHORA)    ║
        // ║ Descomentar cuando se quiera restringir solo a @alumnos... ║
        // ╚══════════════════════════════════════════════════════════════╝
        // $rules['email'][] = function ($attribute, $value, $fail) {
        //     // Solo se permite el dominio institucional de la UTN FRH
        //     $dominioPermitido = '@alumnos.utn.frh.edu.ar';
        //     if (!str_ends_with(strtolower($value), $dominioPermitido)) {
        //         $fail('El email debe ser institucional (' . $dominioPermitido . ').');
        //     }
        // };

        // Ejecutar la validación con los mensajes personalizados
        $validated = $request->validate($rules, [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellido.required'  => 'El apellido es obligatorio.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'Ingresa un email válido.',
            'email.unique'       => 'Este email ya está registrado.',
            'carrera.required'   => 'Seleccioná una carrera.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Generar código de verificación de 6 dígitos aleatorio
        $codigoVerificacion = random_int(100000, 999999);

        // Crear el usuario en la tabla 'users' con rol estudiante
        $user = User::create([
            'name'                       => $validated['nombre'] . ' ' . $validated['apellido'],
            'email'                      => $validated['email'],
            'password'                   => Hash::make($validated['password']),
            'rol'                        => 'estudiante',
            'email_verification_code'    => $codigoVerificacion,
            'email_verification_expires' => now()->addMinutes(30),
        ]);

        // Buscar el id_carrera o crearla dinámicamente si no existe (evita error de FK constraint 1452)
        $carrera = \App\Models\Carrera::firstOrCreate(['nombre' => $validated['carrera']]);

        // Crear el perfil de estudiante vinculado al usuario
        Estudiante::create([
            'id_usuario'       => $user->id,
            'nombre'           => $validated['nombre'],
            'apellido'         => $validated['apellido'],
            'dni'              => $request->input('dni', null), // Se completará después en el perfil
            'legajo'           => $request->input('legajo', 'PENDIENTE'),
            'fecha_nacimiento' => $validated['nacimiento'] ?? null,
            'telefono'         => $validated['telefono'] ?? null,
            'id_carrera'       => $carrera->id_carrera,
        ]);

        // Enviar el email con el código de verificación
        Mail::to($user->email)->send(new VerificacionEmail($user, $codigoVerificacion));

        // Guardar el id del usuario en sesión para la verificación
        session(['verificacion_user_id' => $user->id]);

        // Redirigir a la página de verificación de email
        return redirect()->route('verificacion.mostrar')
            ->with('success', 'Cuenta creada. Revisá tu email para obtener el código de verificación.');
    }

    /**
     * Procesa el registro de una EMPRESA.
     * Valida los datos incluyendo la verificación del CUIT argentino,
     * crea el usuario y el perfil de empresa, y envía código de verificación.
     */
    public function registerEmpresa(Request $request)
    {
        // Reglas de validación para el formulario de empresa
        $validated = $request->validate([
            'nombre_empresa' => ['required', 'string', 'max:100'],
            'razon_social'   => ['required', 'string', 'max:150'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'cuit'           => ['required', 'string'],
            'telefono'       => ['required', 'string', 'max:20'],
            'ubicacion'      => ['nullable', 'string', 'max:200'],
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

        // Validar que el CUIT sea válido usando el algoritmo de dígito verificador
        $cuitLimpio = preg_replace('/[^0-9]/', '', $validated['cuit']);
        if (!CuitValidator::validar($cuitLimpio)) {
            return back()
                ->withErrors(['cuit' => 'El CUIT ingresado no es válido. Verificá el número.'])
                ->withInput();
        }

        // Generar código de verificación de 6 dígitos aleatorio
        $codigoVerificacion = random_int(100000, 999999);

        // Crear el usuario en la tabla 'users' con rol empresa
        $user = User::create([
            'name'                       => $validated['nombre_empresa'],
            'email'                      => $validated['email'],
            'password'                   => Hash::make($validated['password']),
            'rol'                        => 'empresa',
            'email_verification_code'    => $codigoVerificacion,
            'email_verification_expires' => now()->addMinutes(30),
        ]);

        // Crear el perfil de empresa vinculado al usuario
        Empresa::create([
            'id_usuario'          => $user->id,
            'nombre_empresa'      => $validated['nombre_empresa'],
            'razon_social'        => $validated['razon_social'],
            'cuit'                => (int) $cuitLimpio,
            'rubro'               => $request->input('rubro', 'General'),
            'telefono'            => $validated['telefono'],
            'email_contacto'      => $validated['email'],
            'direccion'           => $validated['ubicacion'] ?? null,
            'representante'       => $request->input('representante', $validated['nombre_empresa']),
            'email_representante' => $validated['email'],
        ]);

        // Enviar el email con el código de verificación
        Mail::to($user->email)->send(new VerificacionEmail($user, $codigoVerificacion));

        // Guardar el id del usuario en sesión para la verificación
        session(['verificacion_user_id' => $user->id]);

        // Redirigir a la página de verificación de email
        return redirect()->route('verificacion.mostrar')
            ->with('success', 'Cuenta creada. Revisá tu email para obtener el código de verificación.');
    }
}

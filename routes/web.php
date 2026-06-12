<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificacionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\TicketSoporteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* ════════════════════════════════════════
   RUTAS PÚBLICAS
   Accesibles por cualquier visitante sin necesidad de autenticación.
════════════════════════════════════════ */

Route::get('/', function () {
    return view('index');
})->name('inicio');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

Route::get('/empresas', function () {
    return view('empresas');
})->name('empresas');

/* ════════════════════════════════════════
   AUTH — RUTAS DE INVITADOS (GUEST)
   Solo accesibles si el usuario NO está logueado.
   El middleware 'guest' redirige a usuarios autenticados a su panel.
════════════════════════════════════════ */
Route::middleware('guest')->group(function () {

    // Mostrar el formulario de login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Procesar el formulario de login (POST)
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Mostrar el formulario de registro (con tabs candidato/empresa)
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');

    // Procesar el registro de un estudiante (POST)
    Route::post('/registro/estudiante', [RegisterController::class, 'registerEstudiante'])->name('register.estudiante');

    // Procesar el registro de una empresa (POST)
    Route::post('/registro/empresa', [RegisterController::class, 'registerEmpresa'])->name('register.empresa');

    // ════ RECUPERAR CONTRASEÑA ════
    Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

/* ════════════════════════════════════════
   VERIFICACIÓN DE EMAIL
   Rutas para el flujo de verificación de email con código de 6 dígitos.
   Accesibles sin estar logueado (el usuario aún no verificó su email).
════════════════════════════════════════ */

// Mostrar el formulario para ingresar el código de verificación
Route::get('/verificar-email', [VerificacionController::class, 'mostrar'])->name('verificacion.mostrar');

// Procesar el código de verificación ingresado (POST)
Route::post('/verificar-email', [VerificacionController::class, 'verificar'])->name('verificacion.verificar');

// Reenviar un nuevo código de verificación (POST)
Route::post('/verificar-email/reenviar', [VerificacionController::class, 'reenviar'])->name('verificacion.reenviar');

/* ════════════════════════════════════════
   LOGOUT
   Cierra la sesión del usuario autenticado.
   Requiere estar autenticado (middleware 'auth').
════════════════════════════════════════ */
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* ════════════════════════════════════════
   ESTUDIANTE
   Rutas protegidas para usuarios con rol 'estudiante'.
   Requiere: estar autenticado, email verificado, y rol 'estudiante'.
════════════════════════════════════════ */
Route::prefix('estudiante')
    ->name('estudiante.')
    ->middleware(['auth', 'verified', 'role:estudiante'])
    ->group(function () {
        Route::get('/home',        function () {
            return view('estudiante.home-estudiante');
        })->name('home');
        Route::get('/empresas',    function () {
            return view('estudiante.empresas-lista');
        })->name('empresas');
        Route::get('/perfil',      function () {
            return view('estudiante.perfil-estudiante');
        })->name('perfil');
        Route::get('/mensajes',    function () {
            return view('estudiante.mensajes-estudiante');
        })->name('mensajes');
        Route::get('/oferta/{id}', function ($id) {
            return view('estudiante.oferta-detalle', compact('id'));
        })->name('oferta');

        Route::get('/lista', [App\Http\Controllers\EstudianteController::class, 'lista'])->name('lista');
    });

/* ════════════════════════════════════════
   EMPRESA
   Rutas protegidas para usuarios con rol 'empresa'.
   Requiere: estar autenticado, email verificado, y rol 'empresa'.
════════════════════════════════════════ */
Route::prefix('empresa')
    ->name('empresa.')
    ->middleware(['auth', 'verified', 'role:empresa'])
    ->group(function () {
        Route::get('/home', [App\Http\Controllers\EmpresaController::class, 'home'])->name('home');
        Route::get('/perfil',      function () { return view('empresa.perfil-empresa');     })->name('perfil');
        Route::get('/mensajes',    function () { return view('empresa.mensajes-empresa');   })->name('mensajes');
        Route::get('/postulantes', function () { return view('empresa.postulantes-empresa'); })->name('postulantes');
        Route::get('/ofertas/{id}/postulantes', [EmpresaController::class, 'verPostulantes'])->name('ofertas.postulantes');
        Route::get('/lista', [App\Http\Controllers\EmpresaController::class, 'lista'])->name('lista');
    });

/* ════════════════════════════════════════
   ADMIN
   Rutas protegidas para usuarios con rol 'admin'.
   Requiere: estar autenticado, email verificado, y rol 'admin'.
════════════════════════════════════════ */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/home',       function () {
            return view('admin.admin');
        })->name('home');
        Route::get('/empresas',   function () {
            return view('admin.admin');
        })->name('empresas');
        Route::get('/estudiantes', function () {
            return view('admin.admin');
        })->name('estudiantes');
    });

/* ════════════════════════════════════════
   COMPARTIDAS (LOGUEADOS)
   Rutas accesibles por cualquier usuario autenticado con email verificado,
   sin importar su rol (estudiante, empresa o admin).
════════════════════════════════════════ */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mensajes',      function () {
        return view('mensajes');
    })->name('mensajes');
    Route::get('/notificaciones', function () {
        return view('notificaciones');
    })->name('notificaciones');
    Route::get('/configuracion', function () {
        return view('configuracion');
    })->name('configuracion');
});

/* ════════════════════════════════════════
   FORMULARIO DE CONTACTO (público)
   Procesa el formulario de contacto de la página de ayuda.
════════════════════════════════════════ */
Route::post('/ayuda/contacto', function (Request $request) {
    $request->validate([
        'nombre'  => 'required|min:2',
        'email'   => 'required|email',
        'asunto'  => 'required|min:3',
        'mensaje' => 'required|min:20',
    ]);
    // aquí enviás el mail o guardás en BD
    return back()->with('contacto_ok', true);
})->name('ayuda.contacto');

/* ════════════════════════════════════════
   API RESOURCES
   Rutas de API RESTful para los recursos del sistema.
   Usadas por el frontend con fetch/AJAX.
════════════════════════════════════════ */
Route::prefix('api')->group(function () {
    Route::apiResource('estudiantes', EstudianteController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('empresas', EmpresaController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('ofertas', OfertaController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('chats', ChatController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::apiResource('mensajes', MensajeController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('tickets', TicketSoporteController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
});

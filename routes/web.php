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
use App\Http\Controllers\AdminController;

/* ════════════════════════════════════════
   RUTAS PÚBLICAS
   Accesibles por cualquier visitante sin necesidad de autenticación.
════════════════════════════════════════ */


Route::get('/', [OfertaController::class, 'listar'])->name('inicio');

Route::get('/ayuda', function () {
    return view('ayuda');
})->name('ayuda');

Route::get('/empresas', function () {
    return view('empresas');
})->name('empresas');

// Detalle de oferta — accesible por todos (invitado, estudiante, empresa, admin)
Route::get('/ofertas/{id_oferta}', [OfertaController::class, 'detalle'])->name('ofertas.detalle');

/* ════════════════════════════════════════
   AUTH — RUTAS DE INVITADOS (GUEST)
   Solo accesibles si el usuario NO está logueado.
   El middleware 'guest' redirige a usuarios autenticados a su panel.
════════════════════════════════════════ */
Route::middleware('guest')->group(function () {

    // Mostrar el formulario de login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

    // Procesar el formulario de login (POST)
    Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.post');

    // Pantalla de registro pendiente (empresas)
    Route::get('/registro/pendiente', function () {
        return view('auth.registro-pendiente');
    })->name('registro.pendiente');

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

Route::get('/verificar-email', [VerificacionController::class, 'mostrar'])->name('verificacion.mostrar');
Route::post('/verificar-email', [VerificacionController::class, 'verificar'])->name('verificacion.verificar');
Route::post('/verificar-email/reenviar', [VerificacionController::class, 'reenviar'])->name('verificacion.reenviar');

/* ════════════════════════════════════════
   LOGOUT
════════════════════════════════════════ */
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/* ════════════════════════════════════════
   ESTUDIANTE
   Rutas protegidas para usuarios con rol 'estudiante'.
════════════════════════════════════════ */
Route::prefix('estudiante')
    ->name('estudiante.')
    ->middleware(['auth', 'verified', 'role:estudiante', 'alumno.activo']) // <-- ¡OJO! Acordate de meter acá tu middleware de bloqueo
    ->group(function () {
        Route::get('/home',           function () { return view('estudiante.home-estudiante'); })->name('home');
        Route::get('/empresas',       function () { return view('empresas'); })->name('empresas');
        Route::get('/perfil',         function () { return view('estudiante.perfil-estudiante'); })->name('perfil');
        Route::get('/mensajes',       function () { return view('estudiante.mensajes-estudiante'); })->name('mensajes');
        Route::get('/oferta/{id}',    function ($id) { return view('estudiante.oferta-detalle', compact('id')); })->name('oferta');
        Route::get('/lista',          [EstudianteController::class, 'lista'])->name('lista');
        Route::get('/perfil/editar',  function () { return view('estudiante.perfil-estudiante-editar'); })->name('perfil.editar');
        Route::put('/perfil/update',  [EstudianteController::class, 'updatePerfil'])->name('perfil.update');
        Route::get('/ofertas', [EstudianteController::class, 'obtenerOfertas'])->name('ofertas');
        Route::post('/ofertas/{id_oferta}/postular', [OfertaController::class, 'postular'])->name('ofertas.postular');
    });

/* ════════════════════════════════════════
   EMPRESA
   Rutas protegidas para usuarios con rol 'empresa'.
════════════════════════════════════════ */
Route::prefix('empresa')
    ->name('empresa.')
    ->middleware(['auth', 'verified', 'role:empresa', 'empresa.activa'])
    ->group(function () {
        Route::get('/home',                        [EmpresaController::class, 'home'])->name('home');
        Route::get('/perfil',                      function () { return view('empresa.perfil-empresa'); })->name('perfil');
        Route::get('/mensajes',                    function () { return view('empresa.mensajes-empresa'); })->name('mensajes');
        Route::get('/oferta/{id}/postulantes',     [EmpresaController::class, 'verPostulantes'])->name('empresa.ofertas.postulantes');
        Route::get('/crear-oferta',                function () { return view('empresa.crear-oferta'); })->name('crear-oferta');
        Route::put('/postulacion/{id}/estado',     [EmpresaController::class, 'actualizarEstadoPostulante'])->name('actualizar-estado');
        Route::get('/perfil/editar',               function () { return view('empresa.perfil-empresa-editar'); })->name('perfil.editar');
        Route::put('/perfil/update',               [EmpresaController::class, 'updatePerfil'])->name('perfil.update');
        Route::get('/estudiante/perfil/{id}',      [EstudianteController::class, 'show'])->name('estudiante.perfil');
    });

/* ════════════════════════════════════════
   ADMIN
   Rutas protegidas para usuarios con rol 'admin'.
════════════════════════════════════════ */
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        // Vistas principales existentes
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        Route::get('/empresas', [AdminController::class, 'listarEmpresas'])->name('empresas');
        Route::get('/estudiantes', [AdminController::class, 'listarEstudiantes'])->name('estudiantes');
        
        // NUEVA: Vista de ofertas de empresas aprobadas
        Route::get('/ofertas', [AdminController::class, 'listarOfertas'])->name('ofertas');

        // Acciones existentes...
        Route::post('/estudiantes/{id}/estado', [AdminController::class, 'cambiarEstadoEstudiante'])->name('estudiantes.estado');
        Route::post('/empresas/{id}/estado', [AdminController::class, 'cambiarEstadoEmpresa'])->name('empresas.estado');
        Route::delete('/estudiantes/{id}', [AdminController::class, 'eliminarEstudiante'])->name('estudiantes.destroy');
        Route::delete('/empresas/{id}', [AdminController::class, 'eliminarEmpresa'])->name('empresas.destroy');

        // NUEVAS: Acciones sobre las ofertas
        Route::post('/ofertas/{id}/estado', [AdminController::class, 'cambiarEstadoOferta'])->name('ofertas.estado');
        Route::delete('/ofertas/{id}', [AdminController::class, 'eliminarOferta'])->name('ofertas.destroy');
    });

/* ════════════════════════════════════════
   COMPARTIDAS (LOGUEADOS)
   Accesibles por cualquier usuario autenticado con email verificado.
════════════════════════════════════════ */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mensajes',       function () { return view('mensajes'); })->name('mensajes');
    Route::get('/notificaciones', function () { return view('notificaciones'); })->name('notificaciones');
    Route::get('/configuracion',  function () { return view('configuracion'); })->name('configuracion');

    Route::put('/configuracion/password', function () {
        return back()->with('password_ok', true);
    })->name('configuracion.password');

    Route::post('/configuracion/logout-all', function () {
        return redirect()->route('inicio');
    })->name('configuracion.logout-all');
});

/* ════════════════════════════════════════
   FORMULARIO DE CONTACTO (público)
════════════════════════════════════════ */
Route::post('/ayuda/contacto', function (Request $request) {
    $request->validate([
        'nombre'  => 'required|min:2',
        'email'   => 'required|email',
        'asunto'  => 'required|min:3',
        'mensaje' => 'required|min:20',
    ]);
    return back()->with('contacto_ok', true);
})->name('ayuda.contacto');

/* ════════════════════════════════════════
   API RESOURCES
   Rutas de API RESTful para los recursos del sistema.
   Usadas por el frontend con fetch/AJAX.
════════════════════════════════════════ */
Route::prefix('api')->group(function () {
    Route::apiResource('estudiantes', EstudianteController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('empresas', EmpresaController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('ofertas', OfertaController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('chats', ChatController::class)->only(['index','show','store','destroy']);
    Route::apiResource('mensajes', MensajeController::class)->only(['index','show','store','update','destroy']);
    Route::apiResource('tickets', TicketSoporteController::class)->only(['index','show','store','update','destroy']);
});
